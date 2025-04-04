..  include:: /Includes.rst.txt

..  _configuration:

=============
Configuration
=============

..  warning::
    **Use the provided key pair only for development and testing purposes!**
    Check the :ref:`quick start section <quickConfiguration>` for information on how to create your own key pair.

..  confval:: enabled
    :name: oauth2-enabled
    :type: bool
    :required: false
    :default: true
    :Path: Site settings :yaml:`oauth2_server.enabled`

    Enable oauth2 server.

..  confval:: privateKey
    :name: oauth2-privateKey
    :type: string
    :required: true
    :default: EXT:oauth2_server/Resources/Private/Keys/private.key
    :Path: Site settings :yaml:`oauth2_server.privateKey` or extension configuration :php:`$GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['oauth2_server']['privateKey']`

    Path to the private key file.

..  confval:: publicKey
    :name: oauth2-publicKey
    :type: string
    :required: true
    :default: EXT:oauth2_server/Resources/Private/Keys/public.key
    :Path: Site settings :yaml:`oauth2_server.publicKey` or extension configuration :php:`$GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['oauth2_server']['publicKey']`

    Path to the public key file.

..  confval:: routePrefix
    :name: oauth2-routePrefix
    :type: string
    :required: false
    :default: oauth2
    :Path: Site settings :yaml:`oauth2_server.routePrefix`

    Prefix for the OAuth2 server routes.

..  confval:: accessTokensExpireIn
    :name: oauth2-accessTokensExpireIn
    :type: string
    :required: false
    :default: PT1H
    :Path: Site settings :yaml:`oauth2_server.accessTokensExpireIn`

    Access token lifetime, default is 1 hour.

..  confval:: refreshTokensExpireIn
    :name: oauth2-refreshTokensExpireIn
    :type: string
    :required: false
    :default: P1M
    :Path: Site settings :yaml:`oauth2_server.refreshTokensExpireIn`

    Refresh token lifetime, default is 1 month.

..  confval:: requireCodeChallengeForPublicClients
    :name: oauth2-requireCodeChallengeForPublicClients
    :type: string
    :required: false
    :default: true
    :Path: Site settings :yaml:`oauth2_server.requireCodeChallengeForPublicClients`

    Requires code challenge for public clients by default.

..  confval:: consentPageUid
    :name: oauth2-consentPageUid
    :type: string
    :required: true
    :default: null
    :Path: Site settings :yaml:`oauth2_server.consentPageUid` or extension configuration :php:`$GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['oauth2_server']['consentPageUid']`

    Page UID of the consent page.

..  confval:: loginPageUid
    :name: oauth2-loginPageUid
    :type: string
    :required: true
    :default: null
    :Path: Site settings :yaml:`oauth2_server.loginPageUid` or extension configuration :php:`$GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['oauth2_server']['loginPageUid']`

    Page UID of the login page.

..  confval:: scopes
    :name: oauth2-scopes
    :type: array
    :required: false
    :default: []
    :Path: Site settings :yaml:`oauth2_server.scopes`

    List of scopes.

    .. code-block:: yaml

        oauth2_server:
          scopes:
            - { identifier: 'read', consent: true, description: 'Read access' }

..  confval:: resources
    :name: oauth2-resources
    :type: array
    :required: false
    :default: []
    :Path: Site settings :yaml:`oauth2_server.resources`

    List of yaml configuration files with :ref:`resource routes <resourceRoutes>`.

..  toctree::
   :maxdepth: 5
   :titlesonly:
   :hidden:

   ResourceRoutes
