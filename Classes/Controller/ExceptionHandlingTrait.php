<?php

namespace R3H6\Oauth2Server\Controller;

use TYPO3\CMS\Core\Http\Response;
use League\OAuth2\Server\Exception\OAuthServerException;

trait ExceptionHandlingTrait
{
    /**
     * Perform the given callback with exception handling.
     *
     * @param  \Closure  $callback
     * @return mixed
     */
    protected function withErrorHandling($callback)
    {
        try {
            return $callback();
        } catch (OAuthServerException $exception) {
            return $exception->generateHttpResponse(new Response());
        }
    }
}
