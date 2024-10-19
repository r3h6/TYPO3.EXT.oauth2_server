<?php

declare(strict_types=1);

namespace R3H6\Oauth2Server;

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

final class RequestAttributes
{
    public const OAUTH2_ROUTE = 'oauth2.route';
    public const OAUTH2_GRANT = 'oauth2.grant';
    public const OAUTH_ACCESS_TOKEN_ID = 'oauth_access_token_id';
    public const OAUTH_SCOPES = 'oauth_scopes';
}
