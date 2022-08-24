--TEST--
Date fields supported constraints.
--FILE--
<?php

use Star\Component\Document\Design\Builder\DocumentTypeBuilder;
use Star\Component\Document\Design\Domain\Structure\OutputDocumentType;

require __DIR__ . '/../../vendor/autoload.php';

$document = DocumentTypeBuilder::startDocumentTypeFixture('All dates')
    ->createDate('Optional')->endProperty()
    ->createDate('Required')->required()->endProperty()
    ->createDate('Format')->outputAsFormat('y-m-d')->endProperty()
    ->createDate('Before')->beforeDate('2000-12-31')->endProperty()
    ->createDate('After')->afterDate('1999-01-01')->endProperty()
    ->createDate('Between')->betweenDate('1999-01-01', '1999-12-31')->endProperty()
    ->getDocumentType();
$document->acceptDocumentVisitor(new OutputDocumentType())
?>
--EXPECTF--
Document: "All dates"
Property: Optional (date)
  Constraints:
  Parameters:
Property: Required (date)
  Constraints:
    - required([])
  Parameters:
Property: Format (date)
  Constraints:
  Parameters:
    - format({"format":"y-m-d"})
Property: Before (date)
  Constraints:
    - before-date({"target":"2000-12-31"})
  Parameters:
Property: After (date)
  Constraints:
    - after-date({"target":"1999-01-01"})
  Parameters:
Property: Between (date)
  Constraints:
    - between({"from":"1999-01-01","to":"1999-12-31"})
  Parameters:
