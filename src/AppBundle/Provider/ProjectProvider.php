<?php

namespace AppBundle\Provider;

class ProjectProvider extends BaseProvider
{
    /**
     * @param string $projectSlug
     *
     * @return mixed
     */
    public function getProject($projectSlug)
    {
        return $this->client->get(sprintf('projects/%s', $projectSlug));
    }
}
