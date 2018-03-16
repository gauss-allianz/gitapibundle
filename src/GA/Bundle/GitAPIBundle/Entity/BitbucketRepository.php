<?php
namespace GA\Bundle\GitAPIBundle\Entity;

use GA\Bundle\GitAPIBundle\ErrorCodes;
use GA\Bundle\GitAPIBundle\Entity\Error;

/**
 *  The repository-entity for a Bitbucket repository.
 *  https://bitbucket.org/product
 */

class BitbucketRepository
{
    public $response;

    public function __construct ($repositoryId,
                                 $repoOwner)
    {
        // Prepare API request
        $curlSession = curl_init();
        curl_setopt($curlSession,
                    CURLOPT_URL,
                    "https://api.bitbucket.org/2.0/repositories/".$repoOwner."/".$repositoryId);

        curl_setopt($curlSession,
                    CURLOPT_HTTPHEADER,
                    array('Content-type: application/json',
                          'User-Agent: GitAPIBundle'));

        curl_setopt($curlSession,
                    CURLOPT_RETURNTRANSFER,
                    1);

        // Retrieve data from API
        $response = json_decode(curl_exec($curlSession));

        // Error handling
        if ($response == null)
        {
            $this->response = (new Error(ErrorCodes::ServerOffline,
                                         "The desired server is not available"));

            return $this;
        }

        if (array_key_exists('type',
                             $response) &&
            $response->type == "error")
        {
            if ($response->type == "error" &&
                $response->error->message == "Repository ".$repoOwner."/".$repositoryId." not found")
            {
                // Error: Repository not found
                $this->response = (new Error(ErrorCodes::RepositoryNotFound,
                                             "The desired repository couldn't be found"));

                return $this;
            }
            else
            {
                // Error: API error
                $this->response = (new Error(ErrorCodes::APIError,
                                             "Something went wrong during the API request",
                                             $response));

                return $this;
            }
        }

        curl_setopt($curlSession,
                    CURLOPT_URL,
                    $response->links->forks->href);

        $forksresponse = json_decode(curl_exec($curlSession));

        curl_setopt($curlSession,
                    CURLOPT_URL,
                    $response->links->watchers->href);

        $watchersresponse = json_decode(curl_exec($curlSession));

        if ($forksresponse == null ||
            $watchersresponse == null)
        {
            $this->response = (new Error(ErrorCodes::ServerOffline,
                                         "The desired server is not available"));

            return $this;
        }

        // Extra variables
        $wiki = null;

        if ($response->has_wiki)
        {
            $wiki = $response->links->html->href."/wiki/Home";
        }

        // Prepare response
        $this->response = new Response($response->name,
                                       $response->owner->display_name,
                                       $response->owner->username,
                                       $response->links->html->href,
                                       $response->description,
                                       null,
                                       $forksresponse->size,
                                       null,
                                       $watchersresponse->size,
                                       $response->website,
                                       $response->language,
                                       $response->updated_on,
                                       $response->created_on,
                                       null,
                                       $response->mainbranch->name,
                                       $wiki,
                                       2,
                                       $response);
    }
}
