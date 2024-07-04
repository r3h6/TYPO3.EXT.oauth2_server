..  include:: /Includes.rst.txt

..  _resourceRoutes:

============================
Resource route configuration
============================

Routing is handled by `Symfony's routing component <https://symfony.com/doc/current/routing.html#creating-routes>`__. The configuration is done in YAML files.

For every route you can configure some access restrictions by defining the option :code:`oauth2_constraints`.

..  confval:: oauth2_constraints
    :name: route-oauth2_constraints
    :type: string|array
    :required: false
    :default: oauth.authorized
    :Path: Route configuration :yaml:`[route].options.oauth2_constraints`

    One or more access constraints for the route. The constraints are combined with a logical AND.
    The constraints are evaluated with the `Symfony Expression Language <https://symfony.com/doc/current/components/expression_language.html>`__.

    The following variables are available:

    ..  t3-field-list-table::
        :header-rows: 1

         -  :variable:      Variable
            :type:          Type
            :description:   Description

         -  :variable:      frontend.*
            :type:          object
            :description:   Same as in https://docs.typo3.org/m/typo3/reference-typoscript/main/en-us/Conditions/Index.html#frontend

         -  :variable:      oauth.authorized
            :type:          boolean
            :description:   True if authorization header was set and the bearer token is valid

         -  :variable:      oauth.grant
            :type:          string
            :description:   The grant type of the token

         -  :variable:      oauth.scopes
            :type:          array
            :description:   The scopes of the token

         -  :variable:      request
            :type:          object
            :description:   The server request object

Examples
========

Register route for an endpoint which executes a controller action similiar to eID:

..  code-block:: yaml

    # EXT:my_extension/Configuration/Yaml/Routes.yaml
    example-controller:
        path: /api/v1/simple
        controller: 'Namespace\\MyExtension\\Controller\\ExampleController::handleRequest'
        methods: [GET, POST]
        schemes: https

Protect everything below a certain path and check if scope "read" is present:

..  code-block:: yaml

    # EXT:my_extension/Configuration/Yaml/Routes.yaml
    my-protected-area:
        path: /api/v1/{slug}
        requirements:
            slug: '.*'
        options:
            oauth2_constraints:
                - 'oauth.authorized'
                - '"read" in oauth.scopes'

