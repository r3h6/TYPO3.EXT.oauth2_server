<?php

namespace R3H6\Oauth2Server\Tests\Functional\Domain\Bridge;

use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use R3H6\Oauth2Server\Domain\Bridge\RefreshTokenRepository;
use R3H6\Oauth2Server\Tests\Functional\FunctionalTestCase;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class RefreshTokenRepositoryTest extends FunctionalTestCase
{
    // use \Prophecy\PhpUnit\ProphecyTrait;
    use \R3H6\Oauth2Server\Tests\Functional\FunctionalTestHelper;

    /**
     * @var RefreshTokenRepository
     */
    private $subject;

    protected function setUp(): void
    {
        parent::setUp();
        $this->subject = GeneralUtility::makeInstance(RefreshTokenRepository::class);
    }

    /**
     * @test
     */
    public function getNewTokenReturnsRefreshToken()
    {
        self::assertInstanceOf(RefreshTokenEntityInterface::class, $this->subject->getNewRefreshToken());
    }

    /**
     * @test
     */
    public function persistNewRefreshTokenInsertsDatabaseRecord()
    {
        $identifier = 'aaaabbbbccccddddeeeeffffgggg';

        $accessToken = $this->prophesize(AccessTokenEntityInterface::class);
        $accessToken->getIdentifier()->willReturn('');

        $refreshToken = $this->prophesize(RefreshTokenEntityInterface::class);
        $refreshToken->getIdentifier()->willReturn($identifier);
        $refreshToken->getExpiryDateTime()->willReturn(new \DateTimeImmutable('+1 hour'));
        $refreshToken->getAccessToken()->willReturn($accessToken->reveal());

        $this->subject->persistNewRefreshToken($refreshToken->reveal());

        $this->assertCSVDataSet('EXT:oauth2_server/Tests/Fixtures/DatabaseAssertions/persistNewRefreshTokenInsertsDatabaseRecord.csv');
    }

    /**
     * @test
     */
    public function revokeRefreshTokenUpdatesDatabaseRecord()
    {
        $this->importDataSet('EXT:oauth2_server/Tests/Fixtures/Database/tx_oauth2server_domain_model_refreshtoken.xml');
        $this->subject->revokeRefreshToken('aaaabbbbccccddddeeeeffffgggg');
        $this->assertCSVDataSet('EXT:oauth2_server/Tests/Fixtures/DatabaseAssertions/revokeRefreshTokenUpdatesDatabaseRecord.csv');
    }

    /**
     * @test
     */
    public function isRefreshTokenRevokedReturnsTrueForNonExistingRefreshToken()
    {
        self::assertTrue($this->subject->isRefreshTokenRevoked('idontexists'));
    }

    /**
     * @test
     */
    public function isRefreshTokenRevokedReturnsFalseForNotRevokedRefreshToken()
    {
        $this->importDataSet('EXT:oauth2_server/Tests/Fixtures/Database/tx_oauth2server_domain_model_refreshtoken.xml');
        self::assertFalse($this->subject->isRefreshTokenRevoked('aaaabbbbccccddddeeeeffffgggg'));
    }
    /**
     * @test
     */
    public function isRefreshTokenRevokedReturnsFalseForRevokedRefreshToken()
    {
        $this->importDataSet('EXT:oauth2_server/Tests/Fixtures/Database/tx_oauth2server_domain_model_refreshtoken.xml');
        self::assertTrue($this->subject->isRefreshTokenRevoked('hhhhiiiijjjjkkkkllllmmmmnnnn'));
    }
}
