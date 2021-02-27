<?php

declare(strict_types=1);
namespace R3H6\Oauth2Server;

use League\OAuth2\Server\Exception\OAuthServerException;
use TYPO3\CMS\Core\Http\Response;

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
 * ExceptionHandlingTrait
 */
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
        } catch (\Exception $exception) {
            return (new OAuthServerException($exception->getMessage(), $exception->getCode(), 'unknown_error', 500))
                ->generateHttpResponse(new Response());
            // @codeCoverageIgnoreEnd
        }
    }
}
