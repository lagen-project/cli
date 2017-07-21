<?php

namespace AppBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Yaml\Yaml;

class ConfigurationExtension extends Extension
{
    const CONFIGURATION_FILE = '.lagen.yml';

    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $content = file_get_contents(sprintf('%s/%s', getcwd(), self::CONFIGURATION_FILE));

        if (!$content) {
            throw new FileNotFoundException;
        }

        $config = Yaml::parse($content);

        $container->setParameter('server', $config['server']);
        $container->setParameter('project_slug', $config['project_slug']);
        $container->setParameter('root_dir', $config['root_dir']);
    }
}
