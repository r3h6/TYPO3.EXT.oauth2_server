<?php

declare(strict_types=1);

namespace R3H6\Oauth2Server\Event;

use Psr\Http\Message\ResponseInterface;
use R3H6\Oauth2Server\Configuration\Configuration;
use R3H6\Oauth2Server\Mvc\Controller\AuthorizationContext;

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

final class ModifyConsentRedirectEvent
{
    public function __construct(
        private readonly Configuration $configuration,
        private readonly AuthorizationContext $context,
        private bool $requiresConsent,
        private ResponseInterface $response
    ) {}

    public function getConfiguration(): Configuration
    {
        return $this->configuration;
    }

    public function getContext(): AuthorizationContext
    {
        return $this->context;
    }

    public function getRequiresConsent(): bool
    {
        return $this->requiresConsent;
    }

    public function setRequiresConsent(bool $requiresConsent): void
    {
        $this->requiresConsent = $requiresConsent;
    }

    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }

    public function setResponse(ResponseInterface $response): void
    {
        $this->response = $response;
    }
}
