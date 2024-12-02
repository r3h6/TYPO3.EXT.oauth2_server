..  include:: /Includes.rst.txt

..  _known-problems:

==============
Known problems
==============

Apache + CGI (PHP-FPM)
======================

Note that depending on the webserver and PHP integration therein you might need some additional configuration.

Specifically Apache + CGI (PHP-FPM) needs additional vhost/htaccess configuration in order to have proper authorization header handling.

..  code-block:: plaintext

    SetEnvIfNoCase ^Authorization$ "(.+)" HTTP_AUTHORIZATION=$1

See also: https://symfony.com/doc/current/setup/web_server_configuration.html#using-mod-proxy-fcgi-with-apache-2-4

