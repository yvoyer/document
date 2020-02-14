--TEST--
Text fields supported constraints.
--FILE--
<?php

use Star\Component\Document\Design\Builder\DocumentBuilder;
use Star\Component\Document\Design\Domain\Structure\OutputDocument;

require __DIR__ . '/../../vendor/autoload.php';

$document = DocumentBuilder::createDocument('All texts')
    ->createText('Required')->required()->endProperty()
    ->createText('Regex')->matchesRegex('/\W+/')->endProperty()
    ->getDocument();
$document->acceptDocumentVisitor(new OutputDocument())
?>
--EXPECTF--
Document: "All texts"
Property: Required (string)
  Constraints:
    - required([])
Property: Regex (string)
  Constraints:
    - regex({"pattern":"\/\\W+\/"})
