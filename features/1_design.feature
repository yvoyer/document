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
    When I mark the property "Text" of document "Doc" with constraints:
    | name     | arguments |
    | required | {}        |
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

  Scenario: Create a document with a custom list field
    Given The document "Doc" is created without any properties
    When I create a custom list field named "Custom list" in document "Doc" with the following options:
      | option-id | option-value |
      | 1         | Option 1     |
      | 2         | Option 2     |
      | 3         | Option 3     |
    Then The document "Doc" should have a property "Custom list"

  Scenario: Create a document with a custom list that allows only one option
    Given The document "Doc" is created without any properties
    When I create a custom list field named "Custom list" in document "Doc" with the following options:
      | option-id | option-value |
      | 1         | Option 1     |
      | 2         | Option 2     |
      | 3         | Option 3     |
    When I mark the property "Custom list" of document "Doc" with constraints:
      | name          | arguments   |
      | single-option | {"count":1} |
    Then The document "Doc" should have a property "Custom list"

  Scenario: Change the property definition of a document property
    Given The document "Doc" is created with a custom list property named "List" having the options:
      | option-id | option-value |
      | 1         | Option 1     |
      | 2         | Option 2     |
      | 3         | Option 3     |
    When I mark the property "List" of document "Doc" with constraints:
      | name           | arguments   |
      | required-count | {"count":2} |
    Then The property "List" of document "Doc" should have the following definition:
      | type        | constraint     |
      | custom-list | required-count |
