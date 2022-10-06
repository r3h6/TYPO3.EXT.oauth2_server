<?php

declare(strict_types=1);
namespace R3H6\Oauth2Server\Domain\Factory;

use League\OAuth2\Server\ResourceServer;
use R3H6\Oauth2Server\Configuration\Configuration;

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
 * ResourceServerFactoryInterface
 */
interface ResourceServerFactoryInterface
{
    public function __invoke(Configuration $configuration): ResourceServer;
}
