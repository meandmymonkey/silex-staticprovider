Feature: Browse

    Background: Pages are configured
        Given I have registered a provider at "/" with the config:
            """
            pages:
                home:
                    path: /
                    template: index.html.twig
                page:
                    path: /somewhere
                    template: link.html.twig
            """

    Scenario: All pages exist
        When I go to "/"
        Then the response status code should be 200
        And I should see "index"
        When I go to "/somewhere"
        Then the response status code should be 200
        And I should see "link"

    Scenario: I can navigate with the UrlGenerator
        When I go to "/somewhere"
        And I follow "link"
        Then I should be on "/"
        And the response status code should be 200

    Scenario: Not configured pages are absent
        When I go to "/notfound"
        Then the response status code should be 404
