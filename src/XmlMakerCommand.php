<?php

declare(strict_types=1);

namespace App;


use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class XmlMakerCommand extends Command
{
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

        $xmlWriter->writeAttribute('processIsolation', 'true');
        $xmlWriter->writeAttribute('backupGlobals', 'false');

        $xmlWriter->writeAttribute('colors', 'true');
        $xmlWriter->writeAttribute('stopOnFaliure', 'false');
        $xmlWriter->writeAttribute('verbose', 'true');

        $xmlWriter->endElement();

        $xmlWriter->endDocument();

        $xmlContent = $xmlWriter->flush();

        $fileHandle = fopen('phpunit.xml', 'w');

        fwrite($fileHandle, $xmlContent);

        fclose($fileHandle);

        $output->writeln('xml created successfully');

    }
}
