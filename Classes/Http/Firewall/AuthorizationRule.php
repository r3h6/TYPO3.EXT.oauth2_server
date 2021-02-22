<?php

declare(strict_types=1);
namespace R3H6\Oauth2Server\Http\Firewall;

use Psr\Http\Message\ServerRequestInterface;
use R3H6\Oauth2Server\Exception\AccessDeniedException;

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
  * AuthorizationRule
  */
class AuthorizationRule implements RuleInterface
{
    private $authorization;

    public function __construct($authorization)
    {
        $this->authorization = (bool)$authorization;
    }

    public function __invoke(ServerRequestInterface $request)
    {
        if ($this->authorization && empty($request->getAttribute('oauth_access_token_id'))) {
            throw new AccessDeniedException('It seems the request was never authorized', 1613842256288);
        }
    }
}
