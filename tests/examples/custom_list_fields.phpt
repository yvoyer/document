--TEST--
Custom-list fields supported constraints.
--FILE--
<?php

use Star\Component\Document\Design\Builder\DocumentTypeBuilder;
use Star\Component\Document\Design\Domain\Structure\OutputDocumentType;
use Star\Component\Document\DataEntry\Domain\Model\Values\OptionListValue;

require __DIR__ . '/../../vendor/autoload.php';

$document = DocumentTypeBuilder::startDocumentTypeFixture('All lists')
    ->createListOfOptions('Optional', OptionListValue::withElements(1))->endProperty()
    ->createListOfOptions('Required', OptionListValue::withElements(2))->required()->endProperty()
    ->createListOfOptions('Single option', OptionListValue::withElements(3))->singleOption()->endProperty()
    ->createListOfOptions('Multi option', OptionListValue::withElements(4))->endProperty()
    ->getDocumentType();
$document->acceptDocumentVisitor(new OutputDocumentType())
?>
--EXPECTF--
Document: "All lists"
Property: Optional (list)
  Constraints:
  Parameters:
Property: Required (list)
  Constraints:
    - required({"count":1})
  Parameters:
Property: Single option (list)
  Constraints:
  Parameters:
    - single-option([])
Property: Multi option (list)
  Constraints:
  Parameters:
