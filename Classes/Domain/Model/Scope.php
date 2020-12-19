<?php
namespace R3H6\Oauth2Server\Domain\Model;

use League\OAuth2\Server\Entities\Traits\ScopeTrait;
use League\OAuth2\Server\Entities\Traits\EntityTrait;
use League\OAuth2\Server\Entities\ScopeEntityInterface;

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
 * Scope
 */
final class Scope extends \TYPO3\CMS\Extbase\DomainObject\AbstractValueObject implements ScopeEntityInterface
{
    use EntityTrait;

    public function __construct($name)
    {
        $this->setIdentifier($name);
    }

    public function jsonSerialize()
    {
        return $this->getIdentifier();
    }


}
