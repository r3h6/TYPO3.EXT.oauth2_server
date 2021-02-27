<?php

declare(strict_types=1);
namespace R3H6\Oauth2Server\Http\Firewall;

use League\OAuth2\Server\Exception\OAuthServerException;
use Psr\Http\Message\ServerRequestInterface;

/***
 *
 * This file is part of the "OAuth2 Server" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2020
 *
 ***/

 /**
  * AuthenticatedRule
  */
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
            throw OAuthServerException::accessDenied('No frontend user authentication');
        }
    }
}
