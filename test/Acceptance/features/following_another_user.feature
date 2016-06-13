Feature:
  In order to be continuously updated on some other Twitsup user's fascinating activities
  As a Twitsup user
  I want to be able to follow other users

  Scenario: Follow someone and see tweets appear in the timeline
    Given I've registered myself as matthiasnoback ("Matthias Noback")
    And a user ericcartman ("Eric Cartman") has also registered themselves
    When I follow ericcartman
    And ericcartman tweets "Screw you guys, I'm going home."
    Then I see on my timeline: "Screw you guys, I'm going home."
