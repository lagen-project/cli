<?php

namespace AppBundle\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PushCommand extends BaseCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('push')
            ->setDescription('Update your features')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<fg=cyan>Fetching project...</>');

        try {
            $project = $this->get('app.provider.project')->getProject($this->getParameter('project_id'));
        } catch (\Exception $e) {
            $output->writeln('<fg=red>Unable to fetch project</>');
            $output->writeln($e->getMessage());
            die;
        }
    }
}
