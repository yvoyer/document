--TEST--
Date fields supported constraints.
--FILE--
<?php

use Star\Component\Document\Design\Builder\DocumentBuilder;
use Star\Component\Document\Design\Domain\Structure\OutputDocument;

require __DIR__ . '/../../vendor/autoload.php';

$document = DocumentBuilder::createDocument('All dates')
    ->createDate('Format')->requireFormat('y-m-d')->endProperty()
    ->createDate('Required')->required()->endProperty()
    ->createDate('Past')->beforeDate('now')->endProperty()
    ->createDate('Future')->afterDate('now')->endProperty()
    ->getDocument();
$document->acceptDocumentVisitor(new OutputDocument())
?>
--EXPECTF--
Document: "All dates"
Property: Format (date): date-format
Property: Required (date): required
Property: Past (date): past-date
Property: Future (date): future-date
