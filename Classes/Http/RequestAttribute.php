<?php

declare(strict_types=1);
namespace R3H6\Oauth2Server\Http;

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
 * RequestAttribute
 */
final class RequestAttribute
{
    public const CONFIGURATION = 'oauth2.config';
    public const ROUTE = 'oauth2.route';
    public const TARGET = 'oauth2.target';
}
