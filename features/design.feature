Feature: Designing a document

  Scenario: Create an draft document
    When I create a document named "Doc"
    Then The document "Doc" should have no properties

  Scenario: Create a document with a text field
    Given The document "Doc" is created without any properties
    When I create a text field named "Text" in document "Doc"
    Then The document "Doc" should have a property "Text"

  Scenario: Mark a property as required
    Given The document "Doc" is created with a text property named "Text"
    When I mark the property "Text" as required on the document "Doc"
    Then The document "Doc" should have a required property "Text"

  Scenario: Create a document with a boolean field
    Given The document "Doc" is created without any properties
    When I create a boolean field named "Bool" in document "Doc"
    Then The document "Doc" should have a property "Bool"

  Scenario: Create a document with a date field
    Given The document "Doc" is created without any properties
    When I create a date field named "Date" in document "Doc"
    Then The document "Doc" should have a property "Date"

  Scenario: Create a document with a number field
    Given The document "Doc" is created without any properties
    When I create a number field named "Number" in document "Doc"
    Then The document "Doc" should have a property "Number"
