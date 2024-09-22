<?php

declare(strict_types=1);

namespace R3H6\Oauth2Server\Session;

use League\OAuth2\Server\RequestTypes\AuthorizationRequest;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Authentication\FrontendUserAuthentication;

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

final class Session
{
    public const AUTH_REQUEST = 'oauth2/authRequest';

    public static function fromRequest(ServerRequestInterface $request): self
    {
        $frontendUser = $request->getAttribute('frontend.user');
        if ($frontendUser instanceof FrontendUserAuthentication === false) {
            throw new \RuntimeException('FrontendUserAuthentication not found in request attributes', 1718220842598);
        }
        return GeneralUtility::makeInstance(self::class, $frontendUser);
    }

    public function __construct(
        private readonly FrontendUserAuthentication $frontendUser
    ) {}

    public function get(): ?AuthorizationRequest
    {
        $authRequest = unserialize((string)$this->frontendUser->getKey('ses', self::AUTH_REQUEST));
        if ($authRequest instanceof AuthorizationRequest) {
            return $authRequest;
        }
        return null;
    }

    public function set(AuthorizationRequest $authRequest): void
    {
        $this->frontendUser->setKey('ses', self::AUTH_REQUEST, serialize($authRequest));
    }

    public function clear(): void
    {
        $this->frontendUser->setKey('ses', self::AUTH_REQUEST, null);
    }
}
