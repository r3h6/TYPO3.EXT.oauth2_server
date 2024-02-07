<?php

declare(strict_types=1);
namespace R3H6\Oauth2Server\Tests\Unit\Domain\Repository;

use Prophecy\PhpUnit\ProphecyTrait;
use R3H6\Oauth2Server\Domain\Model\Client;
use R3H6\Oauth2Server\Domain\Model\Scope;
use R3H6\Oauth2Server\Domain\Repository\ScopeRepository;
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
    use ProphecyTrait;
    /**
     * @test
     */
    public function finalizeScopesReturnsSameScopesIfClientAllowedScopesIsEmpty()
    {
        $repository = new ScopeRepository();

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
        $repository = new ScopeRepository();

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
