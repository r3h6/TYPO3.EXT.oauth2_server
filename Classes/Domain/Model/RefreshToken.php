<?php

namespace R3H6\Oauth2Server\Domain\Model;

use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use League\OAuth2\Server\Entities\Traits\EntityTrait;
use League\OAuth2\Server\Entities\Traits\RefreshTokenTrait;

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
 * RefreshToken
 */
final class RefreshToken extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity implements RefreshTokenEntityInterface
{
    use EntityTrait;
    use RefreshTokenTrait;
}
