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

  Scenario: Enter a bool value
    Given The document "My document" is created with a bool property named "My boolean"
    When I enter the following values to document "My document"
      | record-id | property    | value    |
      | 1         | My boolean  | true     |
      | 2         | My boolean  | false    |
      | 3         | My boolean  | 0        |
      | 4         | My boolean  | 1        |
    Then The records list of document the "My document" should looks like:
      | record-id | property    | value    |
      | 1         | My boolean  | true     |
      | 2         | My boolean  | false    |
      | 3         | My boolean  | false    |
      | 4         | My boolean  | true     |

  Scenario: Enter a date value
    Given The document "My document" is created with a date property named "My date"
    When I enter the following values to document "My document"
      | record-id | property | value      |
      | 1         | My date  | 2001-10-01 |
      | 2         | My date  | 2002-01-01 |
    Then The records list of document the "My document" should looks like:
      | record-id | property | value      |
      | 1         | My date  | 2001-10-01 |
      | 2         | My date  | 2002-01-01 |

  Scenario: Enter a number value
    Given The document "My document" is created with a number property named "My number"
    When I enter the following values to document "My document"
      | record-id | property  | value |
      | 1         | My number | 2001  |
      | 2         | My number | 2002  |
    Then The records list of document the "My document" should looks like:
      | record-id | property  | value |
      | 1         | My number | 2001  |
      | 2         | My number | 2002  |

  Scenario: Enter values for a custom list property
    Given The document "My document" is created with a custom list property named "My list" having the options:
      | option-id | option-value |
      | 1         | Option 1     |
      | 2         | Option 2     |
      | 3         | Option 3     |
    When I enter the following values to document "My document"
      | record-id | property  | value |
      | 1         | My list   |       |
      | 2         | My list   | 1     |
      | 3         | My list   | 1;2;3 |
    Then The records list of document the "My document" should looks like:
      | record-id | property  | value                      |
      | 1         | My list   |                            |
      | 2         | My list   | Option 1                   |
      | 3         | My list   | Option 1;Option 2;Option 3 |

  Scenario: Enter a date value to be outputed as year format
    Given The date transformer with id "year" is registered with format "Y-m-d"
    And The document "My document" is created with a date property named "My date"
    And The property "My date" in document "My document" is configured with format "year"
    When I enter the following values to document "My document"
      | record-id | property | value               |
      | 1         | My date  |                     |
      | 2         | My date  | 2002-12-01 12:34:56 |
      | 3         | My date  | 2000-02-29          |
    Then The records list of document the "My document" should looks like:
      | record-id | property | value      |
      | 1         | My date  |            |
      | 2         | My date  | 2002-12-01 |
      | 3         | My date  | 2000-02-29 |

  Scenario: Enter a date value to be outputed as month format
    Given The date transformer with id "month" is registered with format "F"
    And The document "My document" is created with a date property named "My date"
    And The property "My date" in document "My document" is configured with format "month"
    When I enter the following values to document "My document"
      | record-id | property | value               |
      | 1         | My date  |                     |
      | 2         | My date  | 2002-12-01 12:34:56 |
      | 3         | My date  | 2000-02-29          |
    Then The records list of document the "My document" should looks like:
      | record-id | property | value    |
      | 1         | My date  |          |
      | 2         | My date  | December |
      | 3         | My date  | February |

  Scenario: Enter a date value to be outputed as time format
    Given The date transformer with id "time" is registered with format "H:i:s"
    And The document "My document" is created with a date property named "My date"
    And The property "My date" in document "My document" is configured with format "time"
    When I enter the following values to document "My document"
      | record-id | property | value               |
      | 1         | My date  |                     |
      | 2         | My date  | 2002-12-01 12:34:56 |
      | 3         | My date  | 2000-02-29          |
    Then The records list of document the "My document" should looks like:
      | record-id | property | value    |
      | 1         | My date  |          |
      | 2         | My date  | 12:34:56 |
      | 3         | My date  | 00:00:00 |
