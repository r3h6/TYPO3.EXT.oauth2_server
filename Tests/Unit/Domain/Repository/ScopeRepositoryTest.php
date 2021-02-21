<?php

namespace R3H6\Oauth2Server\Tests\Unit;

use R3H6\Oauth2Server\Domain\Model\Scope;
use R3H6\Oauth2Server\Domain\Model\Client;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;
use TYPO3\CMS\Extbase\Object\ObjectManagerInterface;
use R3H6\Oauth2Server\Domain\Repository\ScopeRepository;

class ScopeRepositoryTest extends UnitTestCase
{
    use \Prophecy\PhpUnit\ProphecyTrait;


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
