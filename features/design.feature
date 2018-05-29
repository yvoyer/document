Feature: Designing a document

  Scenario: Create an draft document
    When I create a document named "Doc"
    Then The document "Doc" should have no properties

  Scenario: Create a document with a text field
    Given The document "Doc" is created without any properties
    When I create a text field named "Text" in document "Doc"
   # When I create a text field named "Text" under the section "Section 1" of document "Doc"
    Then The document "Doc" should have a property "Text"

  Scenario: Mark a property as required
    Given The document "Doc" is created with a text property named "Text"
    When I mark the property "Text" as required on the document "Doc"
    Then The document "Doc" should have a required property "Text"

#  Scenario: Create a section on a document
#    Given The document "Doc" has no sections
#    When I create a section named "Section 1" on the document "Doc"
#    Then The document "Doc" should have a root property "Section 1"

