..  include:: /Includes.rst.txt

..  _configuration:

=============
Configuration
=============

..  warning::
    **Use the provided key pair only for development and testing purposes!**
    Check the :ref:`quick start section <quickConfiguration>` for information on how to create your own key pair.

..  confval:: privateKey
    :name: oauth2-privateKey
    :type: string
    :required: true
    :default: EXT:oauth2_server/Resources/Private/Keys/private.key
    :Path: Site configuration :yaml:`oauth2.privateKey` or extension configuration :php:`$GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['oauth2_server']['privateKey']`

    Path to the private key file.

..  confval:: publicKey
    :name: oauth2-publicKey
    :type: string
    :required: true
    :default: EXT:oauth2_server/Resources/Private/Keys/public.key
    :Path: Site configuration :yaml:`oauth2.publicKey` or extension configuration :php:`$GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['oauth2_server']['publicKey']`

    Path to the public key file.

..  confval:: routePrefix
    :name: oauth2-routePrefix
    :type: string
    :required: false
    :default: oauth2
    :Path: Site configuration :yaml:`oauth2.routePrefix`

    Prefix for the OAuth2 server routes.

..  confval:: accessTokensExpireIn
    :name: oauth2-accessTokensExpireIn
    :type: string
    :required: false
    :default: PT1H
    :Path: Site configuration :yaml:`oauth2.accessTokensExpireIn`

    Access token lifetime, default is 1 hour.

..  confval:: refreshTokensExpireIn
    :name: oauth2-refreshTokensExpireIn
    :type: string
    :required: false
    :default: P1M
    :Path: Site configuration :yaml:`oauth2.refreshTokensExpireIn`

    Refresh token lifetime, default is 1 month.

..  confval:: requireCodeChallengeForPublicClients
    :name: oauth2-requireCodeChallengeForPublicClients
    :type: string
    :required: false
    :default: true
    :Path: Site configuration :yaml:`oauth2.requireCodeChallengeForPublicClients`

    Requires code challenge for public clients by default.

..  confval:: consentPageUid
    :name: oauth2-consentPageUid
    :type: string
    :required: true
    :default: null
    :Path: Site configuration :yaml:`oauth2.consentPageUid` or extension configuration :php:`$GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['oauth2_server']['consentPageUid']`

    Page UID of the consent page.

..  confval:: loginPageUid
    :name: oauth2-loginPageUid
    :type: string
    :required: true
    :default: null
    :Path: Site configuration :yaml:`oauth2.loginPageUid` or extension configuration :php:`$GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['oauth2_server']['loginPageUid']`

    Page UID of the login page.

..  confval:: scopes
    :name: oauth2-scopes
    :type: string
    :required: false
    :default: []
    :Path: Site configuration :yaml:`oauth2.scopes`

    List of scopes.

..  confval:: resources
    :name: oauth2-resources
    :type: string
    :required: false
    :default: []
    :Path: Site configuration :yaml:`oauth2.resources`

    List of yaml configuration files with :ref:`resource routes <resourceRoutes>`.

..  toctree::
   :maxdepth: 5
   :titlesonly:
   :hidden:

   ResourceRoutes
