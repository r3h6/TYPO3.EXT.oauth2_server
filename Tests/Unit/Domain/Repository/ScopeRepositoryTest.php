<?php

declare(strict_types=1);
namespace R3H6\Oauth2Server\Tests\Unit\Domain\Repository;

use R3H6\Oauth2Server\Domain\Model\Client;
use R3H6\Oauth2Server\Domain\Model\Scope;
use R3H6\Oauth2Server\Domain\Repository\ScopeRepository;
use TYPO3\CMS\Extbase\Object\ObjectManagerInterface;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

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
 * ScopeRepositoryTest
 */
class ScopeRepositoryTest extends UnitTestCase
{
    /**
     * @test
     */
    public function finalizeScopesReturnsSameScopesIfClientAllowedScopesIsEmpty()
    {
        $objectManager = $this->prophesize(ObjectManagerInterface::class);
        $repository = new ScopeRepository($objectManager->reveal());

        $scopes = [];
        $scopes[] = new Scope('a');
        $scopes[] = new Scope('b');
        $scopes[] = new Scope('c');

        $clientEntity = $this->prophesize(Client::class);
        $clientEntity->getAllowedScopes()->willReturn('');

        $finalizedScopes = $repository->finalizeScopes($scopes, '', $clientEntity->reveal());
        self::assertSame($finalizedScopes, $scopes);
    }

    /**
     * @test
     */
    public function finalizeScopesReturnsOnlyAllowedScopes()
    {
        $objectManager = $this->prophesize(ObjectManagerInterface::class);
        $repository = new ScopeRepository($objectManager->reveal());

        $scopes = [];
        $scopes[] = new Scope('a');
        $scopes[] = new Scope('b');
        $scopes[] = new Scope('c');

        $clientEntity = $this->prophesize(Client::class);
        $clientEntity->getAllowedScopes()->willReturn('a,c');

        $finalizedScopes = $repository->finalizeScopes($scopes, '', $clientEntity->reveal());
        self::assertCount(2, $finalizedScopes);
    }
}
