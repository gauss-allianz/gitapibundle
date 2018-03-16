<?php
namespace GA\Bundle\GitAPIBundle\Entity;

use GA\Bundle\GitAPIBundle\ErrorCodes;
use GA\Bundle\GitAPIBundle\Entity\Error;


/**
 *  The repository-entity for a GitLab repository.
 *  https://about.gitlab.com/
 */

class GitLabRepository
{
    public $response;

    public function __construct ($repositoryId,
                                 $serverAddress,
                                 $privateToken)
    {
        // Prepare API request
        $curlSession = curl_init();
        if ($privateToken === null)
        {
            curl_setopt($curlSession,
                        CURLOPT_URL,
                        "https://".$serverAddress."/api/v4/projects/".$repositoryId);
        }
        else
        {
            curl_setopt($curlSession,
                        CURLOPT_URL,
                        "https://".$serverAddress."/api/v4/projects/".$repositoryId."?private_token=".$privateToken);
        }

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
                             $response) ||
            array_key_exists('error',
                             $response))
        {
            if (array_key_exists('message',
                                 $response) &&
                $response->message == "404 Project Not Found")
            {
                // Error: Repository not found
                $this->response = (new Error(ErrorCodes::RepositoryNotFround,
                                             "The desired repository couldn't be found"));

                return $this;
            }
            else if (array_key_exists('message',
                                      $response))
            {
                // Error: API error
                $this->response = (new Error(ErrorCodes::APIError,
                                             "Something went wrong during the API request",
                                             $response->message));

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

        // Prepare response
        if ($privateToken === null)
        {
            $this->response = new Response($response->name,
                                           null,
                                           null,
                                           $response->web_url,
                                           $response->description,
                                           null,
                                           $response->forks_count,
                                           $response->star_count,
                                           null,
                                           null,
                                           null,
                                           $response->last_activity_at,
                                           $response->created_at,
                                           null,
                                           $response->default_branch,
                                           null,
                                           1,
                                           $response);
        }
        else
        {
            $this->response = new Response($response->name,
                                           null,
                                           null,
                                           $response->web_url,
                                           $response->description,
                                           $response->open_issues_count,
                                           $response->forks_count,
                                           $response->star_count,
                                           null,
                                           null,
                                           null,
                                           $response->last_activity_at,
                                           $response->created_at,
                                           null,
                                           $response->default_branch,
                                           null,
                                           1,
                                           $response);
        }
    }
}
