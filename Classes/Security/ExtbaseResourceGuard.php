<?php

namespace R3H6\Oauth2Server\Security;

use Psr\Http\Message\RequestInterface;
use R3H6\Oauth2Server\ExceptionHandlingTrait;
use TYPO3\CMS\Extbase\Mvc\Exception\StopActionException;
use TYPO3\CMS\Extbase\Mvc\Response;

class ExtbaseResourceGuard
{
    use ExceptionHandlingTrait;

    /**
     * @var ResourceGuard
     */
    private $resourceGuard;

    public function __construct(ResourceGuard $resourceGuard)
    {
        $this->resourceGuard = $resourceGuard;
    }

    public function validateAuthenticatedRequest(RequestInterface $request, Response $response): RequestInterface
    {
        try {
            return $this->resourceGuard->validateAuthenticatedRequest($request);
        } catch (\Exception $exception) {
            $this->fillResponseAndStop($exception, $response);
        }
    }
    public function anyScope(array $scopes, RequestInterface $request, Response $response): void
    {
        try {
            $this->resourceGuard->anyScope($scopes, $request);
        } catch (\Exception $exception) {
            $this->fillResponseAndStop($exception, $response);
        }
    }
    public function allScopes(array $scopes, RequestInterface $request, Response $response): void
    {
        try {
            $this->resourceGuard->allScopes($scopes, $request);
        } catch (\Exception $exception) {
            $this->fillResponseAndStop($exception, $response);
        }
    }

    private function fillResponseAndStop(\Exception $exception, Response $response)
    {
        $errorResponse = $this->withErrorHandling(function () use ($exception) {
            throw $exception;
        });

        $response->setStatus($errorResponse->getStatusCode(), $errorResponse->getReasonPhrase());
        $response->setContent((string)$errorResponse->getBody());
        throw new StopActionException();
    }
}
