<?php

namespace AppBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PullCommand extends BaseCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('pull')
            ->setDescription('Update your locale files by replacing them with the remote features')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<fg=cyan>Fetching project...</>');

        try {
            $project = $this->get('app.provider.project')->getProject($this->getParameter('project_slug'));
        } catch (\Exception $e) {
            $output->writeln('<fg=red>Unable to fetch project</>');
            $output->writeln($e->getMessage());
            die;
        }
        $output->writeln(sprintf('<fg=cyan>Project %s fetched. Fetching features...</>', $project['name']));

        $featureProvider = $this->get('app.provider.feature');

        array_walk($project['features'], function ($feature) use ($input, $output, $featureProvider, $project) {
             try {
                 $featureProvider->importFeature($feature['slug'], $input, $output);
                 $output->writeln(sprintf('<fg=yellow>Fetched "%s"</>', $feature['name']));
             } catch (\Exception $e) {
                 $output->writeln(sprintf('<fg=red>Unable to fetch feature %s</>', $feature['name']));
                 $output->writeln($e->getMessage());
                 die;
             }
        });

        $output->writeln(sprintf('<fg=cyan>Done.</>'));
    }
}
