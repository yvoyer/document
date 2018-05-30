Feature: Enter values on document using property rules
  In order to see my data
  As a user
  I need to enter the data for the document's property

  Scenario: Enter a text value
    Given The document "My document" is created with a text property named "My text"
    When I enter the following values to document "My document"
      | record-id | property | value    |
      | 1         | My text  | Option 1 |
      | 2         | My text  | Option 2 |
      | 3         | My text  | Option 3 |
    Then The records list of document the "My document" should looks like:
      | record-id | property | value    |
      | 1         | My text  | Option 1 |
      | 2         | My text  | Option 2 |
      | 3         | My text  | Option 3 |

