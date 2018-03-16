<?php
namespace GA\Bundle\GitAPIBundle;

use GA\Bundle\GitAPIBundle\ErrorCodes;
use GA\Bundle\GitAPIBundle\GitServices;
use GA\Bundle\GitAPIBundle\Entity\Error;
use GA\Bundle\GitAPIBundle\Entity\Response;
use GA\Bundle\GitAPIBundle\Entity\GitHubRepository;
use GA\Bundle\GitAPIBundle\Entity\GitLabRepository;
use GA\Bundle\GitAPIBundle\Entity\BitbucketRepository;


/**
 *  The client that manages and initializes the requests.
 */

class GitClient
{
    /**
     *  The git-service the data will be retrieved from.
     */
    private $gitService;

    /**
     *  The desired repository's ID/name.
     */
    private $repositoryId;

    /**
     *  The desired repository's owner.
     */
    private $repoOwner;

    /**
     *  The address of the server the data will be retrieved from.
     */
    private $serverAddress;

    /**
     *  The private token needed for accessing exclusive data.
     */
    private $privateToken;

    /**
     *  The complete API-response.
     */
    public $response;

    public function __construct() { }

    public function retrieve($gitService,
                             $repositoryId,
                             $repoOwner = null,
                             $arguments = [])
    {
        // Validate arguments
        if ($gitService === null ||
            $repositoryId === null)
        {
            $this->response = (new Error(ErrorCodes::ClientArgumentError,
                                         "'gitService' and 'repositoryId' can't be null"));
            return $this;
        }
        else if (($gitService === GitServices::GitHub ||
                  $gitService === GitServices::Bitbucket) &&
                 $repoOwner == null)
        {
            $this->response = (new Error(ErrorCodes::ClientArgumentError,
                                         "The selected service (".$gitService.") requires 'repoOwner'"));
            return $this;
        }
        else
        {
            $this->gitService = $gitService;
            $this->repositoryId = $repositoryId;
            $this->repoOwner = $repoOwner;

            // Process arguments
            if ($this->gitService === GitServices::GitLab)
            {
                if (array_key_exists('server_address',
                                     $arguments))
                {
                    $this->serverAddress = $arguments['server_address'];
                }
                else
                {
                    $this->serverAddress = "gitlab.com";
                }

                if (array_key_exists('private_token',
                                     $arguments))
                {
                    $this->privateToken = $arguments['private_token'];
                }
            }

            // Perform request
            switch ($this->gitService)
            {
                case GitServices::GitHub:
                    $this->response = (new GitHubRepository($this->repositoryId,
                                                            $this->repoOwner))->response;
                    break;
                case GitServices::GitLab:
                    $this->response = (new GitLabRepository($this->repositoryId,
                                                            $this->serverAddress,
                                                            $this->privateToken))->response;
                    break;
                case GitServices::Bitbucket:
                    $this->response = (new BitbucketRepository($this->repositoryId,
                                                               $this->repoOwner))->response;
                    break;
            }
        }
    }
}
