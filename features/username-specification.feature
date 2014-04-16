Feature: Username specifications
  In order to enforce security policies
  As a webmaster
  I need to define our username policies

  Background:
    Given a minimal length for username of 6 characters

  Scenario: minimal length policy
    Given a username 'usern'
     When I check if it satisfies our Username Specification
     Then I should get a 'Error: Minimal length' message

  Scenario: special characters policy
    Given a username 'usern@me'
     When I check if it satisfies our Username Specification
     Then I should get a 'Error: Avoid special characters' message
