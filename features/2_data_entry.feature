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
    Then The records list of document "My document" should looks like:
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
      | 3         | My boolean  | false    |
      | 4         | My boolean  | true     |
    Then The records list of document "My document" should looks like:
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
    Then The records list of document "My document" should looks like:
      | record-id | property | value      |
      | 1         | My date  | 2001-10-01 |
      | 2         | My date  | 2002-01-01 |

  Scenario: Enter a number value
    Given The document "My document" is created with a number property named "My number"
    When I enter the following values to document "My document"
      | record-id | property  | value |
      | 1         | My number | 2001  |
      | 2         | My number | 2002  |
    Then The records list of document "My document" should looks like:
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
    Then The records list of document "My document" should looks like:
      | record-id | property  | value                      |
      | 1         | My list   |                            |
      | 2         | My list   | Option 1                   |
      | 3         | My list   | Option 1;Option 2;Option 3 |

  Scenario: Require a text field with a minimum length
    Given The document "document" is created with a text property named "field"
    When I mark the property "field" of document "document" with constraints:
      | name           | value |
      | minimum-length | 3     |
    And I enter the following values to document "document"
      | record-id | property | value |
      | 1         | field    | ABCD  |
      | 2         | field    | ABC   |
      | 3         | field    | A     |
      | 4         | field    |       |
    Then The property "field" of document "document" should have the following definition:
      | type   | constraint     | value |
      | string | minimum-length | 3     |
    And The records list of document "document" should looks like:
      | record-id | property | value |
      | 1         | field    | ABCD  |
      | 2         | field    | ABC   |
    And The record entry should have failed:
      | record-id | property | message |
      | 3         | field    | Property "field" is too short, expected a minimum of 3 characters, "A" given. |
      | 4         | field    | Property "field" is too short, expected a minimum of 3 characters, "" given. |

  Scenario: Require a text field with maximum length
    Given The document "document" is created with a text property named "field"
    When I mark the property "field" of document "document" with constraints:
      | name           | value |
      | maximum-length | 3     |
    And I enter the following values to document "document"
      | record-id | property | value |
      | 1         | field    | ABCD  |
      | 2         | field    | ABC   |
      | 3         | field    | A     |
      | 4         | field    |       |
    Then The records list of document "document" should looks like:
      | record-id | property | value |
      | 2         | field    | ABC   |
      | 3         | field    | A     |
      | 4         | field    |       |
    And The record entry should have failed:
      | record-id | property | message |
      | 1         | field    | Property "field" is too long, expected a maximum of 3 characters, "ABCD" given. |

  Scenario: Require text field to have a format
    Given The document "document" is created with a text property named "field"
    When I mark the property "field" of document "document" with constraints:
      | name  | value |
      | regex | /\d+/ |
    And I enter the following values to document "document"
      | record-id | property | value |
      | 1         | field    | 123   |
      | 2         | field    | ABC   |
      | 3         | field    |       |
    Then The records list of document "document" should looks like:
      | record-id | property | value |
      | 1         | field    | 123   |
      | 3         | field    |       |
    And The record entry should have failed:
      | record-id | property | message |
      | 2         | field    | Value "ABC" do not match pattern "/\d+/". |

  Scenario: Enter a record for a property with before-date constraint
    Given The document "document" is created with a date property named "field"
    When I mark the property "field" of document "document" with constraints:
      | name        | value      |
      | before-date | 2010-07-09 |
    And I enter the following values to document "document"
      | record-id | property | value      |
      | 1         | field    | 2010-07-08 |
      | 2         | field    | 2010-07-09 |
      | 3         | field    | 2010-07-10 |
      | 4         | field    |            |
      | 5         | field    | now        |
      | 6         | field    | +1 day     |
      | 7         | field    | -1 day     |
    Then The records list of document "document" should looks like:
      | record-id | property | value |
      | 2         | field    | ABC   |
      | 3         | field    | A     |
      | 4         | field    |       |
    And The record entry should have failed:
      | record-id | property | message |
      | 1         | field    | Property "field" is too long, expected a maximum of 3 characters, "ABCD" given. |

  Scenario: Enter a record for a property with after-date constraint
    Given The document "document" is created with a date property named "field"
    When I mark the property "field" of document "document" with constraints:
      | name        | value     |
      | after-date | 2010-07-09 |
    And I enter the following values to document "document"
      | record-id | property | value      |
      | 1         | field    | 2010-07-08 |
      | 2         | field    | 2010-07-09 |
      | 3         | field    | 2010-07-10 |
      | 4         | field    |            |
      | 5         | field    | now        |
      | 6         | field    | +1 day     |
      | 7         | field    | -1 day     |
    Then The records list of document "document" should looks like:
      | record-id | property | value |
      | 2         | field    | ABC   |
      | 3         | field    | A     |
      | 4         | field    |       |
    And The record entry should have failed:
      | record-id | property | message |
      | 1         | field    | Property "field" is too long, expected a maximum of 3 characters, "ABCD" given. |

  Scenario: Enter a record for a property with between-date constraint
    Given The document "document" is created with a date property named "field"
    When I mark the property "field" of document "document" with constraints:
      | name         | value      |
      | between-date | 2010-07-09 |
    And I enter the following values to document "document"
      | record-id | property | value      |
      | 1         | field    | 2010-07-08 |
      | 2         | field    | 2010-07-09 |
      | 3         | field    | 2010-07-10 |
      | 4         | field    |            |
      | 5         | field    | now        |
      | 6         | field    | +1 day     |
      | 7         | field    | -1 day     |
    Then The records list of document "document" should looks like:
      | record-id | property | value |
      | 2         | field    | ABC   |
      | 3         | field    | A     |
      | 4         | field    |       |
    And The record entry should have failed:
      | record-id | property | message |
      | 1         | field    | Property "field" is too long, expected a maximum of 3 characters, "ABCD" given. |

  Scenario: Enter a record for a property with between-date constraint
    Given The document "document" is created with a date property named "field"
    When I mark the property "field" of document "document" with constraints:
      | name        | value |
      | date-format | Y-F-d |
    And I enter the following values to document "document"
      | record-id | property | value            |
      | 1         | field    | 2010-07-08       |
      | 2         | field    | 2010-Feb-09      |
      | 3         | field    | December         |
      | 4         | field    | 2001-December-01 |
      | 5         | field    |                  |
    Then The records list of document "document" should looks like:
      | record-id | property | value            |
      | 4         | field    | 2001-December-01 |
      | 5         | field    |                  |
    And The record entry should have failed:
      | record-id | property | message |
      | 1         | field    | The date value "string(2010-07-08)" is not in format "Y-F-d", provided date resulted in "false". |
      | 2         | field    | The date value "string(2010-Feb-09)" is not in format "Y-F-d", provided date resulted in "2010-February-09". |
      | 3         | field    | The date value "string(December)" is not in format "Y-F-d", provided date resulted in "false". |
