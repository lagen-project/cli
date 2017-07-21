<?php

namespace AppBundle\Application;

use AppBundle\Command\PullCommand;
use AppBundle\Command\PushCommand;
use AppBundle\DependencyInjection\AppExtension;
use AppBundle\DependencyInjection\ConfigurationExtension;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;

class ApplicationFactory
{
    /**
     * @param string $name
     * @param string $version
     *
     * @return Application
     */
    public function create($name, $version)
    {
        $application = new Application($name, $version);

        $application->addCommands($this->getCommands());

        $container = new ContainerBuilder();

        foreach ($this->getExtensions() as $extension) {
            $extension->load([], $container);
        }

        $container->compile();

        $application->setContainer($container);

        return $application;
    }

    /**
     * @return Extension[]
     */
    private function getExtensions()
    {
        return [
            new ConfigurationExtension,
            new AppExtension
        ];
    }

    /**
     * @return Command[]
     */
    private function getCommands()
    {
        return [
            new PullCommand
        ];
    }
}
