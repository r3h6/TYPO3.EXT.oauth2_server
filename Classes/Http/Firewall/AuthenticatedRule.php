<?php

namespace R3H6\Oauth2Server\Http\Firewall;

use Psr\Http\Message\ServerRequestInterface;
use R3H6\Oauth2Server\Exception\AccessDeniedException;

class AuthenticatedRule implements RuleInterface
{
    private $authenticated;

    public function __construct($authorized)
    {
        $this->authenticated = (bool)$authorized;
    }

    public function __invoke(ServerRequestInterface $request)
    {
        /** @var \TYPO3\CMS\Frontend\Authentication\FrontendUserAuthentication */
        $frontendUser = $request->getAttribute('frontend.user');
        $isAuthenticated = ($frontendUser->user['uid'] ?? 0) > 0; // Groups are not yet loaded in context api

        if ($this->authenticated && !$isAuthenticated) {
            throw new AccessDeniedException('No frontend user authentication', 1613842994163);
        }
    }
}
