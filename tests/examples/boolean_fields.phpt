--TEST--
Boolean fields supported constraints.
--FILE--
<?php

use Star\Component\Document\Design\Builder\DocumentBuilder;
use Star\Component\Document\Design\Domain\Structure\OutputDocument;

require __DIR__ . '/../../vendor/autoload.php';

$document = DocumentBuilder::createDocument('All boolean')
    ->createBoolean('Optional')->endProperty()
    ->createBoolean('Required')->required()->endProperty()
    ->createBoolean('Labels')->labeled('True', 'False')->endProperty()
    ->createBoolean('Default')->defaultValue(true)->endProperty()
    ->getDocument();
$document->acceptDocumentVisitor(new OutputDocument())
?>
--EXPECTF--
Document: "All boolean"
Property: Optional (boolean)
  Constraints:
  Parameters:
Property: Required (boolean)
  Constraints:
    - required([])
  Parameters:
Property: Labels (boolean)
  Constraints:
  Parameters:
    - label({"true-label":"True","false-label":"False"})
Property: Default (boolean)
  Constraints:
  Parameters:
    - default-value({"value":"true"})
