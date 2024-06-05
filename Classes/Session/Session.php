<?php

namespace R3H6\Oauth2Server\Session;

use League\OAuth2\Server\RequestTypes\AuthorizationRequest;
use Psr\Http\Message\ServerRequestInterface;
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
        return new self($request->getAttribute('frontend.user'));
    }

    private function __construct(
        private readonly FrontendUserAuthentication $frontendUserAuthentication
    ) {}

    public function get(): ?AuthorizationRequest
    {
        $authRequest = unserialize($this->frontendUserAuthentication->getKey('ses', self::AUTH_REQUEST));
        if ($authRequest instanceof AuthorizationRequest) {
            return $authRequest;
        }
        return null;
    }

    public function set(AuthorizationRequest $authRequest): void
    {
        $this->frontendUserAuthentication->setKey('ses', self::AUTH_REQUEST, serialize($authRequest));
    }

    public function clear(): void
    {
        $this->frontendUserAuthentication->setKey('ses', self::AUTH_REQUEST, null);
    }
}
