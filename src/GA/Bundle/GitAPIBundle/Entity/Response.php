<?php
namespace GA\Bundle\GitAPIBundle\Entity;


/**
 *  The response entity that's being returned on successful requests.
 */

class Response
{
    /**
     *  Complete project/repository name (without username).
     */
    public $projectName;

    /**
     *  Complete owner/organization name (e.g. first name and last name).
     */
    public $ownerName;

    /**
     *  Owner/Organization username.
     */
    public $ownerUsername;

    /**
     *  URL of the project/repository.
     */
    public $projectUrl;

    /**
     *  Description of the project/repository.
     */
    public $projectDescription;

    /**
     *  Amount of the project's/repository's open issues.
     */
    public $openIssuesCount;

    /**
     *  Amount of the project's/repository's forks.
     */
    public $forkCount;

    /**
     *  Amount of the project's/repository's stars.
     */
    public $starCount;

    /**
     *  Amount of the project's/repository's watches.
     */
    public $watchCount;

    /**
     *  URL of the project's homepage.
     */
    public $projectHomepage;

    /**
     *  Programming language the project's/repository's working with.
     */
    public $language;

    /**
     *  Date of the latest activity within the project/repository.
     */
    public $lastUpdate;

    /**
     *  Date of the creation of the project/repository.
     */
    public $created;

    /**
     *  Name of the license the project's/repository's published under.
     */
    public $license;

    /**
     *  Name of the default branch of the project/repository.
     */
    public $defaultBranch;

    /**
     *  URL of the project's/repository's wiki.
     */
    public $wikiUrl;

    /**
     *  ID of the Service the data has been retrieved from.
     */
    public $gitService;

    /**
     *  Raw API response.
     */
    public $raw;

    public function __construct($projectName,
                                $ownerName,
                                $ownerUsername,
                                $projectUrl,
                                $projectDescription,
                                $openIssuesCount,
                                $forkCount,
                                $starCount,
                                $watchCount,
                                $projectHomepage,
                                $language,
                                $lastUpdate,
                                $created,
                                $license,
                                $defaultBranch,
                                $wikiUrl,
                                $gitService,
                                $raw)
    {
        $this->projectName = $projectName;
        $this->ownerName = $ownerName;
        $this->ownerUsername = $ownerUsername;
        $this->projectUrl = $projectUrl;
        $this->projectDescription = $projectDescription;
        $this->openIssuesCount = $openIssuesCount;
        $this->forkCount = $forkCount;
        $this->starCount = $starCount;
        $this->watchCount = $watchCount;
        $this->projectHomepage = $projectHomepage;
        $this->language = $language;
        $this->lastUpdate = $lastUpdate;
        $this->created = $created;
        $this->license = $license;
        $this->defaultBranch = $defaultBranch;
        $this->wikiUrl = $wikiUrl;
        $this->gitService = $gitService;
        $this->raw = $raw;
    }

    /**
     *  Returns the response as an array.
     */
    public function toArray()
    {
        return ['type' => "success",
                'project_name' => $this->projectName,
                'owner_name' => $this->ownerName,
                'owner_username' => $this->ownerUsername,
                'project_url' => $this->projectUrl,
                'project_description' => $this->projectDescription,
                'open_issues_count' => $this->openIssuesCount,
                'fork_count' => $this->forkCount,
                'star_count' => $this->starCount,
                'watch_count' => $this->watchCount,
                'project_homepage' => $this->projectHomepage,
                'language' => $this->language,
                'last_update' => $this->lastUpdate,
                'created' => $this->created,
                'license' => $this->license,
                'default_branch' => $this->defaultBranch,
                'wiki_url' => $this->wikiUrl,
                'git_service' => $this->gitService,
                'raw' => $this->raw,];
    }
}
