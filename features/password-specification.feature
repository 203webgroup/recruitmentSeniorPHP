Feature: Password specifications
  In order to enforce security policies
  As a webmaster
  I need to define our password policies

  Background:
    Given a minimal length for password of 6 characters

  Scenario: minimal length policy
    Given a password 'n3wP@'
     When I check if it satisfies our Password Specification
     Then I should get a 'Error: Minimal length' message

  Scenario: special characters policy
    Given a password 'newPassword'
     When I check if it satisfies our Password Specification
     Then I should get a 'Error: Special characters missing' message

  Scenario: case policy
    Given a password 'n3wp@ssw0rd'
     When I check if it satisfies our Password Specification
     Then I should get a 'Error: Upper case characters missing' message

  Scenario: digit policy
    Given a password 'newP@ssword'
     When I check if it satisfies our Password Specification
     Then I should get a 'Error: Digit characters missing' message
