--TEST--
Number fields supported constraints.
--FILE--
<?php

use Star\Component\Document\Design\Builder\DocumentTypeBuilder;
use Star\Component\Document\Design\Domain\Structure\OutputDocumentType;

require __DIR__ . '/../../vendor/autoload.php';

$document = DocumentTypeBuilder::startDocumentTypeFixture('All num')
    ->createNumber('Int')->endProperty()
    ->createNumber('Required')->required()->endProperty()
    ->createNumber('Float')->asFloat()->endProperty()
    ->getDocumentType();
$document->acceptDocumentVisitor(new OutputDocumentType())
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
