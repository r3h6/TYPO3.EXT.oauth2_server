<?php

declare(strict_types=1);

namespace R3H6\Oauth2Server\Tests\Unit\Service;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\NullLogger;
use R3H6\Oauth2Server\Service\Oauth2AuthService;
use TYPO3\CMS\Core\Authentication\AbstractUserAuthentication;

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

class Oauth2AuthServiceTest extends TestCase
{
    private MockObject&Oauth2AuthService $oauth2AuthService;
    private MockObject&ServerRequestInterface $serverRequestMock;

    protected function setUp(): void
    {
        $this->serverRequestMock = $this->createMock(ServerRequestInterface::class);
        $this->oauth2AuthService = $this->getMockBuilder(Oauth2AuthService::class)
            ->onlyMethods(['getRequest', 'fetchUserRecord'])
            ->getMock();

        $this->oauth2AuthService->method('getRequest')
            ->willReturn($this->serverRequestMock);

        $this->oauth2AuthService->setLogger(new NullLogger());
        $this->oauth2AuthService->db_user = [
            'userid_column' => 'uid',
            'check_pid_clause' => '',
        ];
        $pObj = $this->createMock(AbstractUserAuthentication::class);
        $pObj->loginType = 'login';
        $this->oauth2AuthService->pObj = $pObj;
    }

    public function testGetRequest(): void
    {
        self::assertSame($this->serverRequestMock, $this->oauth2AuthService->getRequest());
    }

    public function testGetUserWithUserId(): void
    {
        $this->serverRequestMock->expects(self::once())
            ->method('getAttribute')
            ->with('oauth_user_id')
            ->willReturn(123);

        $this->oauth2AuthService->expects(self::once())
            ->method('fetchUserRecord')
            ->willReturn(['uid' => 123, 'username' => 'testUser123']);

        $result = $this->oauth2AuthService->getUser();
        self::assertIsArray($result);
    }

    public function testGetUserWithoutUserId(): void
    {
        $this->serverRequestMock->expects(self::once())
            ->method('getAttribute')
            ->with('oauth_user_id')
            ->willReturn(null);

        $result = $this->oauth2AuthService->getUser();
        self::assertFalse($result);
    }

    public function testAuthUserWithMatchingUserId(): void
    {
        $this->serverRequestMock->expects(self::once())
            ->method('getAttribute')
            ->with('oauth_user_id')
            ->willReturn(123);

        $user = ['uid' => 123, 'username' => 'testUser123'];

        $result = $this->oauth2AuthService->authUser($user);
        self::assertSame(200, $result);
    }

    public function testAuthUserWithNonMatchingUserId(): void
    {
        $this->serverRequestMock->expects(self::once())
            ->method('getAttribute')
            ->with('oauth_user_id')
            ->willReturn(123);

        $user = ['uid' => 456, 'username' => 'anotherUser'];

        $result = $this->oauth2AuthService->authUser($user);
        self::assertSame(0, $result);
    }

    public function testAuthUserWithoutUserId(): void
    {
        $this->serverRequestMock->expects(self::once())
            ->method('getAttribute')
            ->with('oauth_user_id')
            ->willReturn(null);

        $result = $this->oauth2AuthService->authUser([]);
        self::assertSame(100, $result);
    }
}
