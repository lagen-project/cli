<?php

namespace AppBundle\Command;

use Symfony\Component\Console\Command\Command;

class BaseCommand extends Command
{
    /**
     * @param string $serviceName
     *
     * @return mixed
     */
    protected function get($serviceName)
    {
        return $this->getApplication()->getContainer()->get($serviceName);
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    protected function getParameter($key)
    {
        return $this->getApplication()->getContainer()->getParameter($key);
    }
}
