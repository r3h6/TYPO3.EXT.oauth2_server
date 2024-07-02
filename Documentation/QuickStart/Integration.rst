..  include:: /Includes.rst.txt

.. _quickIntegration:

=================
Quick integration
=================

If you are using `Authorization code grant<https://oauth2.thephpleague.com/authorization-server/auth-code-grant/>`__, following page structure is recommended:

..  code-block:: none

    Login
    ├── Consent
    ├── Users (User Storage Page)
    ├── Clients (Client Storage Page)

Setup the login page
====================

Create a Root TypoScript record and include the TypoScript set provided by this extension.

Add the login form from TYPO3 and make sure you choosed following plugin options:

- Add your "Users" page in the field **User Storage Page**
- Choose **Redirect Mode** "Defined by GET/POST Parameters"
- Check **Use First Supported Mode from Selection**

Setup the consent page
======================

Make the consent page only available for authenticated users by setting the option "Show at any login" in the page properties.

Add the consent form (OAuth2: Consent) from this extension to the page.

Setup the users page
====================

Create this page as "Folder" and add your user and user group records.

Setup the clients page
======================

Create this page as "Folder" and add your client records.
