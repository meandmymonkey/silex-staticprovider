Feature: ContentType

    Background: Pages are configured
        Given I have registered a provider at "/" with the config:
            """
            pages:
                home:
                    path: /
                    template: index.html.twig
                    type: application/json
            """

    Scenario: ContentType is set
        When I go to "/"
        Then the response status code should be 200
        And the "Content-Type" header should contain "application/json"
