..  include:: /Includes.rst.txt

.. _upgrade:

=======================
Upgrade from 1.x to 2.x
=======================


Breaking changes
================


Library updates
---------------

The library ``league/oauth2-server`` has been updated to version ``9.*``.


Resource routes
---------------

The configuration and registration of resource routes has changed.
Configuration is now done in YAML files. See section ":ref:`resourceRoutes`".


Simplified middleware stack
---------------------------

The middleware stack has been simplified. The middleware stack is now:

#. ...
#. :txt:`r3h6/oauth2-server/initializer`
#. ...
#. :txt:`typo3/cms-frontend/authentication`
#. ...
#. :txt:`r3h6/oauth2-server/dispatcher`
#. ...


Removed class ExtbaseGuard
--------------------------

Check the attributes on the request object instead.


Deprecations
============

Configuration in site `config.yaml` under the key `oauth2` is condsidered deprecated.
Use site `settings.yaml` instead under the key `oauth2_server`.
