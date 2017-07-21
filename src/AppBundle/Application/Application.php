<?php

namespace AppBundle\Application;

use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Application extends BaseApplication
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @return ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
    }
}
