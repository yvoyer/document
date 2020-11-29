Feature: Enter values on document using property rules
  In order to see my data
  As a user
  I need to enter the data for the document's property

  Scenario: Enter a text value
    Given The document "My document" is created with a text property named "My text"
    When I enter the following values to document "My document"
      | record-id | values                                            |
      | 1         | {"property":"My text","value":"string(Option 1)"} |
      | 2         | {"property":"My text","value":"string(Option 2)"} |
      | 3         | {"property":"My text","value":"string(Option 3)"} |
    Then The records list of document "My document" should looks like:
      | record-id | property | value    |
      | 1         | My text  | Option 1 |
      | 2         | My text  | Option 2 |
      | 3         | My text  | Option 3 |

  Scenario: Enter a bool value
    Given The document "My document" is created with a bool property named "My boolean"
    When I enter the following values to document "My document"
      | record-id | values                                             |
      | 1         | {"property":"My boolean","value":"boolean(true)"}  |
      | 2         | {"property":"My boolean","value":"boolean(false)"} |
      | 3         | {"property":"My boolean","value":"boolean(false)"} |
      | 4         | {"property":"My boolean","value":"boolean(true)"}  |
    Then The records list of document "My document" should looks like:
      | record-id | property    | value    |
      | 1         | My boolean  | true     |
      | 2         | My boolean  | false    |
      | 3         | My boolean  | false    |
      | 4         | My boolean  | true     |

  Scenario: Enter a date value
    Given The document "My document" is created with a date property named "My date"
    When I enter the following values to document "My document"
      | record-id | values                                            |
      | 1         | {"property":"My date","value":"date(2001-10-01)"} |
      | 2         | {"property":"My date","value":"date(2002-01-01)"} |
    Then The records list of document "My document" should looks like:
      | record-id | property | value      |
      | 1         | My date  | 2001-10-01 |
      | 2         | My date  | 2002-01-01 |

  Scenario: Enter a number value
    Given The document "My document" is created with a number property named "My number"
    When I enter the following values to document "My document"
      | record-id | values                                           |
      | 1         | {"property":"My number","value":"integer(2001)"} |
      | 2         | {"property":"My number","value":"integer(2002)"} |
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
      | record-id | values |
      | 1         | {"property":"My list","value":"empty()"}      |
      | 2         | {"property":"My list","value":"array(1)"}     |
      | 3         | {"property":"My list","value":"array(1;2;3)"} |
    Then The records list of document "My document" should looks like:
      | record-id | property  | value |
      | 1         | My list   |                             |
      | 2         | My list   | [{"id":1,"value":"Option 1","label":"Option 1"}] |
      | 3         | My list   | [{"id":1,"value":"Option 1","label":"Option 1"},{"id":2,"value":"Option 2","label":"Option 2"},{"id":3,"value":"Option 3","label":"Option 3"}] |

  Scenario: Require a text field with a minimum length
    Given The document "document" is created with a text property named "field"
    When I mark the property "field" of document "document" with constraints:
      | name           | arguments    |
      | minimum-length | {"length":3} |
    And I enter the following values to document "document"
      | record-id | values                                      |
      | 1         | {"property":"field","value":"string(ABCD)"} |
      | 2         | {"property":"field","value":"string(ABC)"}  |
      | 3         | {"property":"field","value":"string(A)"}    |
      | 4         | {"property":"field","value":"empty()"}      |
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
      | name           | arguments    |
      | maximum-length | {"length":3} |
    And I enter the following values to document "document"
      | record-id | values                                      |
      | 1         | {"property":"field","value":"string(ABCD)"} |
      | 2         | {"property":"field","value":"string(ABC)"}  |
      | 3         | {"property":"field","value":"string(A)"}    |
      | 4         | {"property":"field","value":"empty()"}      |
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
      | name  | arguments           |
      | regex | {"pattern":"/\\d+/"} |
    And I enter the following values to document "document"
      | record-id | values |
      | 1         | {"property":"field","value":"string(123)"} |
      | 2         | {"property":"field","value":"string(ABC)"} |
      | 3         | {"property":"field","value":"empty()"}     |
    Then The records list of document "document" should looks like:
      | record-id | property | value |
      | 1         | field    | 123   |
    And The record entry should have failed:
      | record-id | property | message |
      | 2         | field    | Value "string(ABC)" do not match pattern "/\d+/". |
      | 3         | field    | Value "empty()" do not match pattern "/\d+/".     |

  Scenario: Enter a record for a property with before-date constraint
    Given The document "document" is created with a date property named "field"
    When I mark the property "field" of document "document" with constraints:
      | name        | arguments               |
      | before-date | {"target":"2010-07-09"} |
    And I enter the following values to document "document"
      | record-id | values                                          |
      | 1         | {"property":"field","value":"date(2010-07-08)"} |
      | 2         | {"property":"field","value":"date(2010-07-09)"} |
      | 3         | {"property":"field","value":"date(2010-07-10)"} |
      | 4         | {"property":"field","value":"empty()"}          |
    Then The records list of document "document" should looks like:
      | record-id | property | value      |
      | 1         | field    | 2010-07-08 |
      | 4         | field    |            |
    And The record entry should have failed:
      | record-id | property | message                                                                               |
      | 2         | field    | The property "field" only accepts date before "2010-07-09", "date(2010-07-09)" given. |
      | 3         | field    | The property "field" only accepts date before "2010-07-09", "date(2010-07-10)" given. |

  Scenario: Enter a record for a property with after-date constraint
    Given The document "document" is created with a date property named "field"
    When I mark the property "field" of document "document" with constraints:
      | name       | arguments               |
      | after-date | {"target":"2010-07-09"} |
    And I enter the following values to document "document"
      | record-id | values                                          |
      | 1         | {"property":"field","value":"date(2010-07-08)"} |
      | 2         | {"property":"field","value":"date(2010-07-09)"} |
      | 3         | {"property":"field","value":"date(2010-07-10)"} |
      | 4         | {"property":"field","value":"empty()"}          |
    Then The records list of document "document" should looks like:
      | record-id | property | value      |
      | 3         | field    | 2010-07-10 |
      | 4         | field    |            |
    And The record entry should have failed:
      | record-id | property | message |
      | 1         | field    | The property "field" only accepts date after "2010-07-09", "date(2010-07-08)" given. |
      | 2         | field    | The property "field" only accepts date after "2010-07-09", "date(2010-07-09)" given. |

  Scenario: Enter a record for a property with between-date constraint
    Given The document "document" is created with a date property named "field"
    When I mark the property "field" of document "document" with constraints:
      | name         | arguments                               |
      | between-date | {"from":"2010-07-08","to":"2010-07-10"} |
    And I enter the following values to document "document"
      | record-id | values                                          |
      | 1         | {"property":"field","value":"date(2010-07-08)"} |
      | 2         | {"property":"field","value":"date(2010-07-09)"} |
      | 3         | {"property":"field","value":"date(2010-07-10)"} |
      | 4         | {"property":"field","value":"empty()"}          |
    Then The records list of document "document" should looks like:
      | record-id | property | value |
      | 2         | field    | 2010-07-09 |
      | 4         | field    |            |
    And The record entry should have failed:
      | record-id | property | message                                                                               |
      | 1         | field    | The property "field" only accepts date after "2010-07-08", "date(2010-07-08)" given.  |
      | 3         | field    | The property "field" only accepts date before "2010-07-10", "date(2010-07-10)" given. |

  Scenario: Enter a record for a property with between-date constraint
    Given The document "document" is created with a date property named "field"
    When I mark the property "field" of document "document" with parameters:
      | name        | arguments |
      | date-format | {"format":"Y-F-d"} |
    And I enter the following values to document "document"
      | record-id | values                                           |
      | 1         | {"property":"field","value":"date(2010-07-08)"}  |
      | 2         | {"property":"field","value":"date(2010-02-09)"}  |
      | 3         | {"property":"field","value":"date(2012-12-23)"}  |
      | 4         | {"property":"field","value":"date(2001-12-01)"}  |
      | 5         | {"property":"field","value":"empty()"}           |
    Then The records list of document "document" should looks like:
      | record-id | property | value            |
      | 1         | field    | 2010-July-08     |
      | 2         | field    | 2010-February-09 |
      | 3         | field    | 2012-December-23 |
      | 4         | field    | 2001-December-01 |
      | 5         | field    |                  |
    And The record entry should have failed:
      | record-id | property | message |

  Scenario: Enter a record for a property with default value when not set
    Given The document "document" is created with a bool property named "field"
    When I mark the property "field" of document "document" with parameters:
      | name          | arguments         |
      | default-value | {"value-class":"Star\\Component\\Document\\DataEntry\\Domain\\Model\\Values\\BooleanValue","value":"true"} |
    And I enter the following values to document "document"
      | record-id | values                                        |
      | 1         | {"property":"field","value":"empty()"}        |
      | 2         | {"property":"field","value":"boolean(true)"}  |
      | 3         | {"property":"field","value":"boolean(false)"} |
    Then The records list of document "document" should looks like:
      | record-id | property | value |
      | 1         | field    | true  |
      | 2         | field    | true  |
      | 3         | field    | false |
