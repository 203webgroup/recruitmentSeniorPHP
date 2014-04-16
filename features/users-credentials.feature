Feature: User credentials
  In order to enforce security policies
  As a webmaster
  I need to define credentials specifications

  Scenario: Change password
    Given a user named 'xavier'
      And a new password 'n3wP@ssw0rd' that satisfies our Password Specification
     When I change his password credentials for the new one
     Then the result should be successfully

  Scenario: Change username
    Given a user named 'xavier'
      And a new username 'newUsername' that satisfies our Username Specification
     When I change his username credentials for the new one
     Then the result should be successfully