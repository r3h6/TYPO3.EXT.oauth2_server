<?php

declare(strict_types=1);

namespace R3H6\Oauth2Server\Domain\Oauth;

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

enum TokenTypes: string
{
    case ACCESS_TOKEN = 'access_token';
    case REFRESH_TOKEN = 'refresh_token';
}