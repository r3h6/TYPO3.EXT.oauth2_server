<?php

namespace R3H6\Oauth2Server\Domain\Oauth;

enum TokenTypes: string
{
    case ACCESS_TOKEN = 'access_token';
    case REFRESH_TOKEN = 'refresh_token';
}
