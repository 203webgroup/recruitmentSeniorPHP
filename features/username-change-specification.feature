Feature: Change username specifications
  In order to enforce security policies
  As a webmaster
  I need to define our policies when changing a username

  Background:
    Given a user named 'duplicateUsername'
      And exists a user named 'duplicateUsername' in the User Collection

  Scenario: mismatch policy
    Given a username 'newUsername' and its username confirmation 'wr@ngUsername'
     When I check if it satisfies our Change Username Specification
     Then I should get a 'Error: username mismatch' message

  Scenario: uniqueness policy
    Given a username 'duplicateUsername' and its username confirmation 'duplicateUsername'
     When I check if it satisfies our Change Username Specification
     Then I should get a 'Error: not unique' message

  Scenario: satisfy username policy
    Given a username 'wr@ngUsername' and its username confirmation 'wr@ngUsername'
      And the username 'wr@ngUsername' does not satisfy Username Specification
     When I check if it satisfies our Change Username Specification
     Then I should get a 'Error: Avoid special characters' message
