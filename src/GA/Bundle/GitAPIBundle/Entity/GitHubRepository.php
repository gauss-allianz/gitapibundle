<?php
namespace GA\Bundle\GitAPIBundle\Entity;

use GA\Bundle\GitAPIBundle\ErrorCodes;
use GA\Bundle\GitAPIBundle\Entity\Error;
use GA\Bundle\GitAPIBundle\Entity\Response;


/**
 *  The repository-entity for a GitHub repository.
 *  https://github.com/
 */

class GitHubRepository
{
    public $response;

    public function __construct ($repositoryId,
                                 $repoOwner)
    {
        // Prepare API request
        $curlSession = curl_init();
        curl_setopt($curlSession,
                    CURLOPT_URL,
                    "https://api.github.com/repos/".$repoOwner."/".$repositoryId);

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

        if (array_key_exists('message',
                             $response))
        {
            if ($response->message == "Not Found")
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

        // Extra variables
        $wiki = null;

        if ($response->has_wiki)
        {
            $wiki = $response->html_url."/wiki";
        }

        // Prepare response
        $this->response = new Response($response->name,
                                       null,
                                       $response->owner->login,
                                       $response->html_url,
                                       $response->description,
                                       $response->open_issues,
                                       $response->forks,
                                       $response->stargazers_count,
                                       $response->subscribers_count,
                                       $response->homepage,
                                       $response->language,
                                       $response->updated_at,
                                       $response->created_at,
                                       $response->license->name,
                                       $response->default_branch,
                                       $wiki,
                                       0,
                                       $response);
    }
}
