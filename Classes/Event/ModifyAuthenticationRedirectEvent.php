<?php

namespace R3H6\Oauth2Server\Event;

use Psr\Http\Message\ResponseInterface;
use R3H6\Oauth2Server\Configuration\Configuration;
use R3H6\Oauth2Server\Mvc\Controller\AuthorizationContext;

final class ModifyAuthenticationRedirectEvent
{
    public function __construct(
        private readonly Configuration $configuration,
        private readonly AuthorizationContext $context,
        private bool $requiresAuthentication,
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

    public function getRequiresAuthentication(): bool
    {
        return $this->requiresAuthentication;
    }

    public function setRequiresAuthentication(bool $requiresAuthentication): void
    {
        $this->requiresAuthentication = $requiresAuthentication;
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
