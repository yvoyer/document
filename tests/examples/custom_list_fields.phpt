--TEST--
Custom-list fields supported constraints.
--FILE--
<?php

use Star\Component\Document\Design\Builder\DocumentBuilder;
use Star\Component\Document\Design\Domain\Structure\OutputDocument;

require __DIR__ . '/../../vendor/autoload.php';

$document = DocumentBuilder::createDocument('All lists')
    ->createCustomList('Required')->required()->endProperty()
    ->createCustomList('Single option')->singleOption()->endProperty()
    ->getDocument();
$document->acceptDocumentVisitor(new OutputDocument())
?>
--EXPECTF--
Document: "All dates"
Property: Format (date)
  Constraints:
    - date-format({"format":"y-m-d"})
Property: Required (date)
  Constraints:
    - required([])
Property: Past (date)
  Constraints:
    - past-date({"target":"2000-12-31"})
Property: Future (date)
  Constraints:
    - future-date({"target":"1999-01-01"})
