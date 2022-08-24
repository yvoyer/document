--TEST--
Boolean fields supported constraints.
--FILE--
<?php

use Star\Component\Document\Design\Builder\DocumentTypeBuilder;
use Star\Component\Document\Design\Domain\Structure\OutputDocumentType;

require __DIR__ . '/../../vendor/autoload.php';

$document = DocumentTypeBuilder::startDocumentTypeFixture('All boolean')
    ->createBoolean('Optional')->endProperty()
    ->createBoolean('Required')->required()->endProperty()
    ->createBoolean('Labels')->labeled('True', 'False')->endProperty()
    ->createBoolean('Default')->defaultValue(true)->endProperty()
    ->getDocumentType();
$document->acceptDocumentVisitor(new OutputDocumentType())
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
    - default-value({"value":"true","value-class":"Star\\Component\\Document\\DataEntry\\Domain\\Model\\Values\\BooleanValue"})
