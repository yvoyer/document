Feature: Designing a document

  Scenario: Create an draft document
    When I create a document named "Doc"
    Then The structure of the document "Doc" should look like:
    """
    Doc
    """

  Scenario: Create a section on a document
    Given The document "Doc" is created
    When I create a section named "Section 1" on the document "Doc"
    Then The structure of the document "Doc" should look like:
    """
    Doc
      `-- Section 1
    """

  Scenario: Create a document with a text field
    Given The document "Doc" is created
    And The document "Doc" has a section named "Section 1"
    When I create a text field named "Text" under the section "Section 1" of document "Doc"
    Then The structure of the document "Doc" should look like:
    """
    Doc
      `-- Section 1
            `-- Text
    """

  Scenario: Update the properties of a field
    Given The document "Doc" is created
    And The document "Doc" has a section named "Section 1"
    And The document "Doc" as a text field named "Text" under the section "Section 1"
    When I update the property required of the field "Text" on the document "Doc"
    Then The structure of the document "Doc" should look like:
    """
    Doc
      `-- Section 1
            `-- Text (required)
    """

