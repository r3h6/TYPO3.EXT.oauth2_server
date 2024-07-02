..  include:: /Includes.rst.txt

.. _quickConfiguration:

===================
Quick configuration
===================


1.  Create your own `public and private keys<https://oauth2.thephpleague.com/installation/#generating-public-and-private-keys>`__.
    Change the permissions of the keys to :code:`600` or :code:`660`

2.  Enable the OAuth2 server for your site by adding following minimal site configuration:

    ..  code-block:: yaml
        # sites/[site]/config.yaml
        oauth2: []

3.  Set the path to your key files in the extension configuration or in the site configuration

    ..  code-block:: yaml
        # sites/[site]/config.yaml
        oauth2:
            privateKey: '/path/to/private.key'
            publicKey: '/path/to/public.key'

4.  Configure your resources by providing a list of yaml configuration files with :ref:`resource routes <resourceRoutes>`.

    ..  code-block:: yaml
        # sites/[site]/config.yaml
        oauth2:
            resources:
                - 'EXT:my_extension/Configuration/Yaml/Routes.yaml'


