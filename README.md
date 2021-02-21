# OAuth2 Server

Oauth2 server for TYPO3.

## Installation

**Only composer supported!**

```bash
$ composer require r3h6/oauth2-server
```

## Integration



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
    - { identifier: scope2, description: 'Description or LLL path'}

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

