<?php

namespace AppBundle\Provider;

use AppBundle\Client\Client;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Filesystem\Filesystem;

class FeatureProvider extends BaseProvider
{
    /**
     * @var string
     */
    private $projectSlug;

    /**
     * @var string
     */
    private $featuresRootDir;

    /**
     * @var Filesystem
     */
    private $fs;

    /**
     * @var QuestionHelper
     */
    private $questionHelper;

    public function __construct(Client $client, $projectSlug, $featuresRootDir)
    {
        parent::__construct($client);

        $this->projectSlug = $projectSlug;
        $this->featuresRootDir = $featuresRootDir;
        $this->fs = new Filesystem();
        $this->questionHelper = new QuestionHelper();
    }

    /**
     * @param string $featureSlug
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    public function importFeature($featureSlug, InputInterface $input, OutputInterface $output)
    {
        $featureContent = $this
            ->client
            ->getPlain(sprintf('projects/%s/features/%s/export', $this->projectSlug, $featureSlug));

        if (!$this->fs->exists($this->featuresRootDir)) {
            $this->fs->mkdir($this->featuresRootDir);
        }

        $featureName = preg_replace(sprintf('/^Feature: ([^%s]+)%s.*$/s', PHP_EOL, PHP_EOL), '$1', $featureContent);

        $featuresMetadata = $this->getFeatureMetadata($featureSlug);

        if (!$featuresMetadata) {
            $question = new Question(
                sprintf('What directory for feature "%s" ? [%s] ', $featureName, $this->featuresRootDir),
                $this->featuresRootDir
            );
            $dir = $this->questionHelper->ask($input, $output, $question);
            $question = new Question(
                sprintf('What file name for feature "%s" ? [%s] ', $featureName, $featureSlug),
                $featureSlug
            );
            $filename = $this->questionHelper->ask($input, $output, $question);

            $featuresMetadata = [
                'dir' => $dir,
                'filename' => $filename
            ];
            $this->setFeatureMetadata($featureSlug, $featuresMetadata);
        }

        if (!$this->fs->exists($featuresMetadata['dir'])) {
            $this->fs->mkdir($featuresMetadata['dir']);
        }

        file_put_contents(sprintf('%s/%s', $featuresMetadata['dir'], $featuresMetadata['filename']), $featureContent);
    }

    /**
     * @param string $featureSlug
     * @param array $metadata
     */
    public function setFeatureMetadata($featureSlug, array $metadata)
    {
        $this
            ->client
            ->post(sprintf('projects/%s/features/%s/metadata', $this->projectSlug, $featureSlug), [
                'form_params' => [
                    'metadata' => $metadata
                ]
            ]);
    }

    /**
     * @param string $featureSlug
     *
     * @return array
     */
    public function getFeatureMetadata($featureSlug)
    {
        return $this
            ->client
            ->get(sprintf('projects/%s/features/%s/metadata', $this->projectSlug, $featureSlug))
        ;
    }
}
