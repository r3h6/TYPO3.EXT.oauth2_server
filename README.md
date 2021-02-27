# OAuth2 Server

OAuth2 server for TYPO3 based on [PHP League's OAuth2 Server](https://oauth2.thephpleague.com/).

Features:
- Supports all grant types from PHP League's OAuth2 Server
- Can be used to protect API's from other extensions
- Clients can be limited to certain scopes


## Installation

**Only composer supported!**

```bash
$ composer require r3h6/oauth2-server
```


## Integration

Create your own [public and private keys](https://oauth2.thephpleague.com/installation/#generating-public-and-private-keys).<br>
Use the provided key pair only for development.

You must explicit enable the OAuth2 server in your site configuration yaml by adding at least following configuration:

For the authorization code grant you must create a frontend login and a consent page.<br>
This extensions provides a Typoscript setup with a basic design.


```yaml
oauth2: []
```

## Endpoints

Endpoint | Description
--- | ---
/oauth2/authorize | GET = Start authorization, POST = Accept, DELETE = Deny
/oauth2/token | Issues token
/oauth2/revoke | Revokes an access token

## Configuration

```yaml
oauth2:
  # Path to private key
  # Type: string
  privateKey: 'EXT:oauth2_server/Resources/Private/Keys/private.key'

  # Path to public key
  # Type: string
  publicKey: 'EXT:oauth2_server/Resources/Private/Keys/public.key'

  # Access token lifetime
  # Type: string
  accessTokensExpireIn: 'P1M'

  # Refresh token lifetime
  # Type: string
  refreshTokensExpireIn: 'P1M'

  # Page uid with "Oauth2: Consent" plugin
  # Type: int
  consentPageUid: 0

  # Page uid for frontend login (otherwise users are redirected to the root page)
  # Type: int
  loginPageUid: 0

  # Scopes
  # Type: array
  scopes:
    - scope1
    - { identifier: scope2, description: 'Description or LLL path', consent: true }

  # Configuration for protected resources
  resources:

    # Resource name
    my_resource:

      # Resource route, string, a regex matching the request path
      # Type: string
      path: /rest/.*

      # Resource methods (optional)
      # Type: string|array
      methods: POST|GET

      # Resource target (optional)
      # Type: string
      target: Controller::action

      # Firewall rule, checks if a user is authenticated (optional)
      # Type: boolean
      authenticated: false

      # Firewall rule, check if client ip matches given pattern (optional)
      # Type: string
      ip: '127.*'

      # Firewall rule, check if request is using https (optional)
      # Type: boolean
      https: true

      # Firewall rule, check if access token has at least one of the scopes (optional)
      # Type: string|array
      scope: 'read|write'

      # Firewall rule, check if access token has all scopes (optional)
      # Type: string|array
      scope: 'read,write'
```


## Protecting resources from Extbase plugins.

Extbase plugins with routing can still be called through query parameters.<br>
Such requests bypass the request validation of this extension.<br>
You should therefore make some htaccess rules denying such request,<br>
implement the request validation by yourself or<br>
use the ExtbaseGuard to check if the request passed the validation.

```php
class ExtbaseController extends ActionController
{
    /**
     * @var \R3H6\Oauth2Server\Security\ExtbaseGuard
     * @TYPO3\CMS\Extbase\Annotation\Inject
     */
    protected $guard;

    public function initializeAction()
    {
        $this->guard->checkAccess($GLOBALS['TYPO3_REQUEST'], 'my_resource', $this->response);
    }
}

```

## Middlewares

This extensions adds several middlewares to the stack.
In order to work correctly the must be executed in the expected order.

```
...
typo3/cms-frontend/site
...
r3h6/oauth2-server/configuration
r3h6/oauth2-server/routing
r3h6/oauth2-server/authentication
...
typo3/cms-frontend/authentication
...
r3h6/oauth2-server/firewall
r3h6/oauth2-server/dispatcher
...
typo3/cms-frontend/base-redirect-resolver
...
```
