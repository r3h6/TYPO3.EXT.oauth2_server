<?php

declare(strict_types=1);
namespace R3H6\Oauth2Server\Tests\Unit\Controller;

use Psr\Log\LoggerInterface;
use TYPO3\CMS\Core\Http\Uri;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Http\RedirectResponse;
use Psr\Http\Message\ServerRequestInterface;
use R3H6\Oauth2Server\Http\RequestAttribute;
use League\OAuth2\Server\AuthorizationServer;
use R3H6\Oauth2Server\Configuration\Configuration;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;
use R3H6\Oauth2Server\Domain\Repository\UserRepository;
use R3H6\Oauth2Server\Controller\AuthorizationController;
use League\OAuth2\Server\RequestTypes\AuthorizationRequest;
use R3H6\Oauth2Server\Domain\Repository\AccessTokenRepository;
use TYPO3\CMS\Frontend\Authentication\FrontendUserAuthentication;

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
 * AuthorizationControllerTest
 */
class AuthorizationControllerTest extends UnitTestCase
{
    /**
     * @var AuthorizationController
     */
    private $subject;

    /**
     * @var AuthorizationServer
     */
    private $server;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var AccessTokenRepository
     */
    private $accessTokenRepository;

    protected $resetSingletonInstances = true;

    public function setUp(): void
    {
        parent::setUp();

        $GLOBALS['TYPO3_CONF_VARS']['SYS']['encryptionKey'] = 'aa06c08658128b1247afeb704b26475edfa8b70afb5369ea66bb7a8098950cdb75b7ec73140a352b6fb51aa5b9f69042';

        $logger = $this->prophesize(LoggerInterface::class);

        $this->server = $this->prophesize(AuthorizationServer::class);
        $this->userRepository = $this->prophesize(UserRepository::class);
        $this->accessTokenRepository = $this->prophesize(AccessTokenRepository::class);

        $this->subject = new AuthorizationController($this->userRepository->reveal(), $this->accessTokenRepository->reveal(), $this->server->reveal());
        $this->subject->setLogger($logger->reveal());
    }

    /**
     * @test
     */
    public function startAuthorizationWillReturnRedirectToConsent()
    {
        $frontenUser = $this->prophesize(FrontendUserAuthentication::class);

        $request = $this->prophesize(ServerRequestInterface::class);
        $request->getAttribute('frontend.user')->willReturn($frontenUser->reveal());

        $configuration = $this->prophesize(Configuration::class);
        $configuration->getConsentPageUid()->willReturn(0);
        $configuration->getLoginPageUid()->willReturn(0);
        $request->getAttribute(RequestAttribute::CONFIGURATION)->willReturn($configuration->reveal());

        $site = $this->prophesize(Site::class);
        $request->getAttribute('site')->willReturn($site->reveal());

        $request->getUri()->willReturn(new Uri('http://localhost/'));

        $authRequest = $this->prophesize(AuthorizationRequest::class);
        $this->server->validateAuthorizationRequest($request->reveal())->willReturn($authRequest->reveal());

        $response = $this->subject->startAuthorization($request->reveal());

        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertRegExp('#/\?redirect_url=#', $response->getHeader('Location')[0]);
        $frontenUser->setAndSaveSessionData('oauth2/authRequest', $authRequest->reveal())->shouldHaveBeenCalled();
    }
}
