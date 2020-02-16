--TEST--
Custom-list fields supported constraints.
--FILE--
<?php

use Star\Component\Document\Design\Builder\DocumentBuilder;
use Star\Component\Document\Design\Domain\Structure\OutputDocument;

require __DIR__ . '/../../vendor/autoload.php';

$document = DocumentBuilder::createDocument('All lists')
    ->createCustomList('Optional', 'option 1')->endProperty()
    ->createCustomList('Required', 'option 1')->required()->endProperty()
    ->createCustomList('Allow multi-option', 'option 2')->allowMultiOption()->endProperty()
    ->getDocument();
$document->acceptDocumentVisitor(new OutputDocument())
?>
--EXPECTF--
Document: "All lists"
Property: Optional (list)
  Constraints:
  Parameters:
Property: Required (list)
  Constraints:
    - required-count({"count":1})
  Parameters:
Property: Allow multi-option (list)
  Constraints:
  Parameters:
    - allow-multiple([])
