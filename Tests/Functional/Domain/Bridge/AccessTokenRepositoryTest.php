<?php

namespace R3H6\Oauth2Server\Tests\Functional\Domain\Bridge;

use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use R3H6\Oauth2Server\Domain\Bridge\AccessTokenRepository;
use R3H6\Oauth2Server\Tests\Functional\FunctionalTestCase;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class AccessTokenRepositoryTest extends FunctionalTestCase
{
    // use \Prophecy\PhpUnit\ProphecyTrait;
    use \R3H6\Oauth2Server\Tests\Functional\FunctionalTestHelper;

    /**
     * @var AccessTokenRepository
     */
    private $subject;

    protected function setUp(): void
    {
        parent::setUp();
        $this->subject = GeneralUtility::makeInstance(AccessTokenRepository::class);
    }

    /**
     * @test
     */
    public function getNewTokenReturnsAccessToken()
    {
        $client = $this->prophesize(ClientEntityInterface::class)->reveal();
        $scopes = [
            $this->createScopeMock('test')
        ];
        $userIdentifier = 123;

        $accessToken = $this->subject->getNewToken($client, $scopes, $userIdentifier);

        self::assertInstanceOf(AccessTokenEntityInterface::class, $accessToken);
        self::assertSame($userIdentifier, $accessToken->getUserIdentifier());
        self::assertSame($scopes, $accessToken->getScopes());
        self::assertSame($client, $accessToken->getClient());
    }

    /**
     * @test
     */
    public function persistNewAccessTokenInsertsDatabaseRecord()
    {
        $identifier = 'aaaabbbbccccddddeeeeffffgggg';

        $client = $this->prophesize(ClientEntityInterface::class);
        $client->getIdentifier()->willReturn('660e56d72c12f9a1e2ec');

        $accessToken = $this->prophesize(AccessTokenEntityInterface::class);
        $accessToken->getIdentifier()->willReturn($identifier);
        $accessToken->getExpiryDateTime()->willReturn(new \DateTimeImmutable('+1 hour'));
        $accessToken->getUserIdentifier()->willReturn(123);
        $accessToken->getScopes()->willReturn([]);
        $accessToken->getClient()->willReturn($client->reveal());

        $this->subject->persistNewAccessToken($accessToken->reveal());

        $this->assertCSVDataSet('EXT:oauth2_server/Tests/Fixtures/DatabaseAssertions/persistNewAccessTokenInsertsDatabaseRecord.csv');
    }

    /**
     * @test
     */
    public function revokeAccessTokenUpdatesDatabaseRecord()
    {
        $this->importDataSet('EXT:oauth2_server/Tests/Fixtures/Database/tx_oauth2server_domain_model_accesstoken.xml');
        $this->subject->revokeAccessToken('aaaabbbbccccddddeeeeffffgggg');
        $this->assertCSVDataSet('EXT:oauth2_server/Tests/Fixtures/DatabaseAssertions/revokeAccessTokenUpdatesDatabaseRecord.csv');
    }

    /**
     * @test
     */
    public function isAccessTokenRevokedReturnsTrueForNonExistingAccessToken()
    {
        self::assertTrue($this->subject->isAccessTokenRevoked('idontexists'));
    }

    /**
     * @test
     */
    public function isAccessTokenRevokedReturnsFalseForNotRevokedAccessToken()
    {
        $this->importDataSet('EXT:oauth2_server/Tests/Fixtures/Database/tx_oauth2server_domain_model_accesstoken.xml');
        self::assertFalse($this->subject->isAccessTokenRevoked('aaaabbbbccccddddeeeeffffgggg'));
    }
    /**
     * @test
     */
    public function isAccessTokenRevokedReturnsFalseForRevokedAccessToken()
    {
        $this->importDataSet('EXT:oauth2_server/Tests/Fixtures/Database/tx_oauth2server_domain_model_accesstoken.xml');
        self::assertTrue($this->subject->isAccessTokenRevoked('hhhhiiiijjjjkkkkllllmmmmnnnn'));
    }
}
