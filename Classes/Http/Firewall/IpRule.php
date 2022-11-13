<?php

declare(strict_types=1);
namespace R3H6\Oauth2Server\Http\Firewall;

use League\OAuth2\Server\Exception\OAuthServerException;
use Psr\Http\Message\ServerRequestInterface;
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
 * IpRule
 */
class IpRule implements RuleInterface
{
    private $ip;

    public function __construct($ip)
    {
        $this->ip = (string)$ip;
    }

    public function __invoke(ServerRequestInterface $request)
    {
        if ($this->ip && !GeneralUtility::cmpIP(GeneralUtility::getIndpEnv('REMOTE_ADDR'), $this->ip)) {
            throw OAuthServerException::accessDenied('IP restriction');
        }
    }
}
