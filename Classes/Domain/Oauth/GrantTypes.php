<?php

namespace R3H6\Oauth2Server\Domain\Oauth;

enum GrantTypes: string
{
    case AUTHORIZATION_CODE = 'authorization_code';
    case CLIENT_CREDENTIALS = 'client_credentials';
    case IMPLICIT = 'implicit';
    case PASSWORD = 'password';
    case REFRESH_TOKEN = 'refresh_token';
}
