<?php

declare(strict_types=1);

namespace R3H6\Oauth2Server\Tests\Unit\Domain\Repository;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use R3H6\Oauth2Server\Domain\Model\Client;
use R3H6\Oauth2Server\Domain\Repository\ClientRepository;
use TYPO3\CMS\Core\Crypto\PasswordHashing\PasswordHashFactory;
use TYPO3\CMS\Core\Crypto\PasswordHashing\PasswordHashInterface;
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

class ClientRepositoryTest extends TestCase
{
    private MockObject&ClientRepository $clientRepository;
    private MockObject&LoggerInterface $logger;
    private MockObject&PasswordHashFactory $passwordHashFactory;
    private MockObject&PasswordHashInterface $passwordHash;

    protected function setUp(): void
    {
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->passwordHashFactory = $this->createMock(PasswordHashFactory::class);
        $this->passwordHash = $this->createMock(PasswordHashInterface::class);

        $this->clientRepository = $this->getMockBuilder(ClientRepository::class)
            ->setConstructorArgs([$this->logger])
            ->onlyMethods(['findOneBy'])
            ->getMock();

        GeneralUtility::addInstance(PasswordHashFactory::class, $this->passwordHashFactory);
    }

    public function testValidateClient(): void
    {
        $client = $this->createMock(Client::class);
        $client->method('getGrantType')->willReturn('password');
        $client->method('getSecret')->willReturn('hashed_password');

        $this->clientRepository->method('findOneBy')->willReturn($client);

        $this->passwordHashFactory->method('getDefaultHashInstance')->willReturn($this->passwordHash);
        $this->passwordHash->method('checkPassword')->willReturn(true);

        $result = $this->clientRepository->validateClient('client_id', 'client_secret', 'password');

        self::assertTrue($result);
    }

    // Add more test methods for each scenario you want to test
}
