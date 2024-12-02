<?php

declare(strict_types=1);

namespace R3H6\Oauth2Server\Routing;

use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\Routing\Route;

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

interface RouterInterface
{
    public function match(ServerRequestInterface $request): ?Route;
}
