<?php

namespace R3H6\Oauth2Server\Service;

use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Token\Parser;
use Lcobucci\JWT\UnencryptedToken;
use League\OAuth2\Server\CryptTrait;
use R3H6\Oauth2Server\Domain\Oauth\TokenTypes;

class TokenService
{
    use CryptTrait;

    public function __construct()
    {
        $this->encryptionKey = $GLOBALS['TYPO3_CONF_VARS']['SYS']['encryptionKey'];
    }

    public function getTokenType(string $token): TokenTypes
    {
        $jwtRegex = '#^[A-Za-z0-9-_=]+\.[A-Za-z0-9-_=]+(\.[A-Za-z0-9-_.+/=]+)?$#';
        if (preg_match($jwtRegex, $token)) {
            return TokenTypes::ACCESS_TOKEN;
        }
        return TokenTypes::REFRESH_TOKEN;
    }

    public function decodeAccessToken(string $token): ?UnencryptedToken
    {
        if (empty($token)) {
            return null;
        }
        $jwt = (new Parser(new JoseEncoder()))->parse($token);
        if (!$jwt instanceof UnencryptedToken) {
            return null;
        }
        return $jwt;
    }

    public function decodeRefreshToken(string $token): ?\stdClass
    {
        $decryptedToken = $this->decrypt($token);
        try {
            $decodedToken = json_decode($decryptedToken, false, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException) {
            return null;
        }
        return $decodedToken;
    }
}
