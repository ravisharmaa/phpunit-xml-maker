<?php

$writer = new \XMLWriter();

$writer->openMemory();

$writer->startDocument('1.0','UTF-8');
$writer->setIndent(true);
$writer->startElement('phpunit');
$writer->writeAttribute('category', 'test');
$writer->endElement();
$writer->writeAttribute('category', 'test');
$writer->writeAttribute('category', 'test');
$writer->writeAttribute('category', 'test');
$writer->startElement("main");
$writer->writeElement('user_id', 3);
$writer->writeElement('msg_count', 11);
$writer->endElement();
$writer->startElement("msg");
$writer->writeAttribute('category', 'test');
$writer->endElement();
$writer->endElement();
$writer->endDocument();


$xmlContent = $writer->flush();

$fileHandle = fopen('test.xml', 'w');

fwrite($fileHandle, $xmlContent);

fclose($fileHandle);