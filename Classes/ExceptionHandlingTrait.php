<?php

namespace R3H6\Oauth2Server;

use League\OAuth2\Server\Exception\OAuthServerException;
use TYPO3\CMS\Core\Http\Response;

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
            // @codeCoverageIgnoreStart
        } catch (Exception $exception) {
            return (new OAuthServerException($exception->getMessage(), 0, 'unknown_error', 500))
                ->generateHttpResponse(new Response());
            // @codeCoverageIgnoreEnd
        }
    }
}
