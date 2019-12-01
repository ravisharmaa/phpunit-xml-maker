<?php

declare(strict_types=1);

namespace App;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class XmlMakerCommand extends Command
{
    protected array $defaults = [
        'processIsolation' => 'true',
        'backupGlobals' => 'true',
        'verbose' => 'true',
        'stopOnFaliure' => 'false',
        'colors' => 'true',
    ];

    protected function configure()
    {
        $this->setName('make:xml')
            ->setDescription('Makes phpunit.xml file for creating your test configuration')
            ->setHelp('This command makes xml file for phpunit');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $xmlWriter = new \XMLWriter();

        $xmlWriter->openMemory();

        $xmlWriter->startDocument('1.0', 'UTF-8');

        $xmlWriter->startElement('phpunit');

        $proceedWithDefaults = $this->askQuestion($output->writeln('<info>foo</info>'), 'true', $input, $output);

        if ('true' === $proceedWithDefaults) {
            foreach ($this->defaults as $option => $answer) {
                $xmlWriter->writeAttribute($option, $answer);
            }
        }

        $xmlWriter->writeAttribute('processIsolation',
            $this->askQuestion('Should process isolation be enabled? ', 'true', $input, $output));

        $xmlWriter->writeAttribute('backupGlobals',
            $this->askQuestion('Should I backup globals? ', 'true', $input, $output));

        $xmlWriter->writeAttribute('colors',
            $this->askQuestion('Should I enable colors? ', 'true', $input, $output));

        $xmlWriter->writeAttribute('stopOnFaliure', 'false');

        $xmlWriter->writeAttribute('verbose', 'true');

        $xmlWriter->endElement();

        $xmlWriter->endDocument();

        $xmlContent = $xmlWriter->flush();

        $fileHandle = fopen('phpunit.xml', 'w');

        fwrite($fileHandle, $xmlContent);

        fclose($fileHandle);

        return $output->writeln('xml created successfully');
    }

    public function askQuestion(string $question, string $defaultAnswer, InputInterface $input, OutputInterface $output): string
    {
        $questionHelper = $this->getHelper('question');

        $question = new Question($question, $defaultAnswer);

        $answer = $questionHelper->ask($input, $output, $question);

        return ('yes' === $answer) ? 'true' : 'false';
    }
}
