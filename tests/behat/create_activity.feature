@mod @mod_clickview
Feature: Teachers can create a new ClickView activity

  @javascript
  Scenario: Teachers can add a new ClickView activity
    Given the following config values are set as admin:
      | hostlocation | https://online.clickview.co.uk | local_clickview |
    Given the following "users" exist:
      | username | firstname | lastname | email |
      | teacher1 | Teacher | 1 | teacher1@example.com |
    And the following "courses" exist:
      | fullname | shortname | category |
      | Course 1 | C1 | 0 |
    And the following "course enrolments" exist:
      | user | course | role |
      | teacher1 | C1 | editingteacher |
    When I log in as "teacher1"
    And I am on "Course 1" course homepage
    And I press "Turn editing on"
    And I press "Add an activity or resource"
    And I click on "Add a new ClickView Video" "link" in the "Add an activity or resource" "dialogue"
    And I set the field "Name" to "Test ClickView name"
    And I press "Save and return to course"
    Then I should see "Test ClickView name" in the "region-main" "region"
    And I log out
