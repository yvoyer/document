--TEST--
Boolean fields supported constraints.
--FILE--
<?php

use Star\Component\Document\Design\Builder\DocumentBuilder;
use Star\Component\Document\Design\Domain\Structure\OutputDocument;

require __DIR__ . '/../../vendor/autoload.php';

$document = DocumentBuilder::createDocument('All boolean')
    ->createBoolean('Required')->required()->endProperty()
    ->createBoolean('Labels')->labeled('True', 'False')->endProperty()
    ->createBoolean('Default')->defaultValue(true)->endProperty()
    ->getDocument();
$document->acceptDocumentVisitor(new OutputDocument())
?>
--EXPECTF--
Document: "All boolean"
Property: Required (boolean)
  Constraints:
    - required([])
Property: Labels (boolean)
  Parameters:
    - label({"true":"True","false":"False"}})
Property: Default (boolean)
  Parameters:
    - default({"value":boolean(true)})
