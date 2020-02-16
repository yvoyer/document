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
  Parameters:
Property: Required (number)
  Constraints:
    - required([])
  Parameters:
Property: Float (number)
  Constraints:
    - number-format({"decimal":2,"point":".","thousands_separator":","})
  Parameters:
