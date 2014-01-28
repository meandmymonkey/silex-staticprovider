Feature: Cache headers

    Background: Pages are configured
        Given I have registered a provider at "/" with the config:
            """
            pages:
                home:
                    path: /
                    template: index.html.twig
            cache:
                s_maxage: 10
            """

    Scenario: Cache headers exist
        When I go to "/"
        Then the response status code should be 200
        And the "Cache-Control" header should contain "public, s-maxage=10"
