<?php

declare(strict_types=1);
namespace R3H6\Oauth2Server\Tests\Functional\Domain\Bridge;

use Prophecy\PhpUnit\ProphecyTrait;
use R3H6\Oauth2Server\Tests\Functional\FunctionalTestHelper;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use R3H6\Oauth2Server\Domain\Bridge\RefreshTokenRepository;
use R3H6\Oauth2Server\Tests\Functional\FunctionalTestCase;
use TYPO3\CMS\Core\Utility\GeneralUtility;

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
 * RefreshTokenRepositoryTest
 */
class RefreshTokenRepositoryTest extends FunctionalTestCase
{
    use ProphecyTrait;
    use FunctionalTestHelper;

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
        $this->importCSVDataSet(GeneralUtility::getFileAbsFileName('EXT:oauth2_server/Tests/Fixtures/Database/tx_oauth2server_domain_model_refreshtoken.csv'));
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
        $this->importCSVDataSet(GeneralUtility::getFileAbsFileName('EXT:oauth2_server/Tests/Fixtures/Database/tx_oauth2server_domain_model_refreshtoken.csv'));
        self::assertFalse($this->subject->isRefreshTokenRevoked('aaaabbbbccccddddeeeeffffgggg'));
    }
    /**
     * @test
     */
    public function isRefreshTokenRevokedReturnsFalseForRevokedRefreshToken()
    {
        $this->importCSVDataSet(GeneralUtility::getFileAbsFileName('EXT:oauth2_server/Tests/Fixtures/Database/tx_oauth2server_domain_model_refreshtoken.csv'));
        self::assertTrue($this->subject->isRefreshTokenRevoked('hhhhiiiijjjjkkkkllllmmmmnnnn'));
    }
}
