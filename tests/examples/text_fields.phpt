--TEST--
Text fields supported constraints.
--FILE--
<?php

use Star\Component\Document\Design\Builder\DocumentTypeBuilder;
use Star\Component\Document\Design\Domain\Structure\OutputDocumentType;

require __DIR__ . '/../../vendor/autoload.php';

$document = DocumentTypeBuilder::startDocumentTypeFixture('All texts')
    ->createText('Optional')->endProperty()
    ->createText('Required')->required()->endProperty()
    ->createText('Regex')->matchesRegex('/\W+/')->endProperty()
    ->getDocumentType();
$document->acceptDocumentVisitor(new OutputDocumentType())
?>
--EXPECTF--
Document: "All texts"
Property: Optional (string)
  Constraints:
  Parameters:
Property: Required (string)
  Constraints:
    - required([])
  Parameters:
Property: Regex (string)
  Constraints:
    - regex({"pattern":"\/\\W+\/"})
  Parameters:
