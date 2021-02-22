<?php

declare(strict_types=1);
namespace R3H6\Oauth2Server\Http\Firewall;

use Psr\Http\Message\ServerRequestInterface;
use R3H6\Oauth2Server\Exception\AccessDeniedException;
use TYPO3\CMS\Core\Utility\GeneralUtility;

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
  * ScopesRule
  */
class ScopesRule implements RuleInterface
{
    private $scopes;

    public function __construct($scopes)
    {
        $this->scopes = is_array($scopes) ? $scopes : GeneralUtility::trimExplode(',', $scopes);
    }

    public function __invoke(ServerRequestInterface $request)
    {
        $tokenScopes = $request->getAttribute('oauth_scopes', []);
        if (!empty($this->scopes) && count(array_intersect($this->scopes, $tokenScopes)) !== count($this->scopes)) {
            throw new AccessDeniedException('Require scopes "' . implode('", "', $this->scopes) . '"', 1613596676896);
        }
    }
}
