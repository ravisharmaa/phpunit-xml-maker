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
        'bootstrap' => 'vendor/autoload.php',
        'processIsolation' => 'true',
        'backupGlobals' => 'true',
        'verbose' => 'true',
        'stopOnFaliure' => 'false',
        'colors' => 'true',
        'testsuiteName' => 'Application test Suite',
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
        $xmlWriter->setIndent(true);

        $xmlWriter->startElement('phpunit');


//        $proceedWithDefaults = $this->askQuestion('Should I opt in with the defaults? ', 'true', $input, $output);
//
//        if ('true' === $proceedWithDefaults) {
//            foreach ($this->defaults as $option => $answer) {
//                $xmlWriter->writeAttribute($option, $answer);
//
//                if ('testsuiteName' === $option) {
//                    $xmlWriter->setIndent(true);
//                    $xmlWriter->startElement('testsuites');
//                    $xmlWriter->startElement('testsuite');
//                    $xmlWriter->writeElement('name', $answer);
//                    $xmlWriter->writeElement('directory','tests');
//                    $xmlWriter->endElement();
//                    $xmlWriter->endElement();
//                }
//            }
//        }

        $xmlWriter->writeAttribute('processIsolation',
            $this->askQuestion('Should process isolation be enabled? ', 'true', $input, $output));

        $xmlWriter->writeAttribute('backupGlobals',
            $this->askQuestion('Should I backup globals? ', 'true', $input, $output));

        $xmlWriter->writeAttribute('colors',
            $this->askQuestion('Should I enable colors? ', 'true', $input, $output));

        $xmlWriter->writeAttribute('stopOnFaliure', 'false');

        $xmlWriter->writeAttribute('verbose', 'true');

        $xmlWriter->startElement('testsuites');
        $xmlWriter->startElement('testsuite');
        $xmlWriter->writeElement('directory','tests');
        $xmlWriter->writeAttribute('suffix', 'Test.php');
        $xmlWriter->endElement();
        $xmlWriter->endElement();
        $xmlWriter->endElement();
        $xmlWriter->endElement();

        $xmlWriter->endElement();

        $xmlContent = $xmlWriter->flush();

        $fileHandle = fopen('phpunit.xml', 'w');

        fwrite($fileHandle, $xmlContent);
        die();

        fclose($fileHandle);

        return true;
    }

    public function askQuestion(string $question, string $defaultAnswer, InputInterface $input, OutputInterface $output): string
    {
        $questionHelper = $this->getHelper('question');

        $question = new Question($question, $defaultAnswer);

        $answer = $questionHelper->ask($input, $output, $question);

        return ('yes' === $answer) ? 'true' : 'false';
    }
}
