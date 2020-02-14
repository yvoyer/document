--TEST--
Number fields supported constraints.
--FILE--
<?php

use Star\Component\Document\Design\Builder\DocumentBuilder;
use Star\Component\Document\Design\Domain\Structure\OutputDocument;

require __DIR__ . '/../../vendor/autoload.php';

$document = DocumentBuilder::createDocument('All num')
    ->createNumber('Int')->endProperty()
    ->createNumber('Required')->required()->endProperty()
    ->createNumber('Float')->asFloat()->endProperty()
    ->getDocument();
$document->acceptDocumentVisitor(new OutputDocument())
?>
--EXPECTF--
Document: "All num"
Property: Int (number)
  Constraints:
Property: Required (number)
  Constraints:
    - required([])
Property: Float (number)
  Constraints:
    - float({"decimal":2,"point":".","thousands_separator":","})
