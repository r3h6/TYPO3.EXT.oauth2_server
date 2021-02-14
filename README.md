# OAuth2 Server

Todo:
- Protect resources with https


## Installation

```bash
$ composer require r3h6/oauth2-server
```

## Configuration

```yaml
oauth2:
    # Path to private key
    privateKey: 'EXT:oauth2_server/Resources/Private/Keys/private.key'

    # Path to public key
    publicKey: 'EXT:oauth2_server/Resources/Private/Keys/public.key'

    # Class name for oauth2 server which implements the /authorize and /token endpoint
    server: R3H6\Oauth2Server\Http\Oauth2Server

    # Prefix for oauth2 server
    routePrefix: 'oauth2'

    # Access token lifetime
    accessTokensExpireIn: 'P1M'

    # Refresh token lifetime
    refreshTokensExpireIn: 'P1M'

    # Implicit grant is disabled by default
    enableImplicitGrantType: false

    # Page uid with "Oauth2: Consent" plugin
    consentPageUid: null

    # Scopes
    scopes: []

    # Firewall rules
    firewall: []

    # Resources
    #
    # resources:
    #   rule_name: 'Vendor\Extension\Controller\EndpointController::actionMethod'
    resources: []

```

## Protecting resources

Resources can be protected either through firewall rules or prorgrammatically.

### Firewall rules

Firewall rules are usefull to protect 3rd party extension endpoints like "rest" or
own endpoints registered under the "resources" configuration.

```yaml
oauth2:
  firewall:
    rule_name:
      # A regex matching the url path
      path: /api/v1/.*
      # A space seperated list of query parameters (optional)
      query: tx_example_pi1
      # Request method must be in list (optional)
      methods: GET|POST
      # Access token must have at least one of the listed scopes (optional)
      scope: read|write
      # Access token must have all listed scopes (optional)
      scopes: read write
```

### Prorgrammatically

```php
class EndpointController implements ResourceGuardAwareInterface
{
    use ResourceGuardAwareTrait;

    public function actionMethod(ServerRequestInterface $request): ResponseInterface
    {
        $request = $this->resourceGuard->validateAuthenticatedRequest($request);
        $this->resourceGuard->anyScope(['read'], $request);
        $this->resourceGuard->allScopes(['write', 'endpoint'], $request);
    }
}
```

```php
class ExtbaseController extends ActionController
{
    /**
     * @var \R3H6\Oauth2Server\Security\ExtbaseResourceGuard
     * @TYPO3\CMS\Extbase\Annotation\Inject
     */
    protected $resourceGuard;

    public function initializeAction()
    {
        $request = $this->resourceGuard->validateAuthenticatedRequest($GLOBALS['TYPO3_REQUEST'], $this->response);
        $this->resourceGuard->anyScope(['read'], $request, $this->response);
        $this->resourceGuard->allScopes(['write', 'endpoint'], $request, $this->response);
    }
}
```
