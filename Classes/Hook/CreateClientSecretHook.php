<?php

declare(strict_types=1);
namespace R3H6\Oauth2Server\Hook;

use TYPO3\CMS\Core\Crypto\PasswordHashing\PasswordHashFactory;
use TYPO3\CMS\Core\Crypto\Random;
use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Messaging\FlashMessageService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

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
 * CreateClientSecretHook
 */
class CreateClientSecretHook
{

    /**
     * @var \TYPO3\CMS\Core\Messaging\FlashMessageService
     */
    protected $flashMessageService;

    /**
     * @var \TYPO3\CMS\Core\Crypto\Random
     */
    protected $random;

    public function __construct(FlashMessageService $flashMessageService, Random $random)
    {
        $this->flashMessageService = $flashMessageService;
        $this->random = $random;
    }

    public function processDatamap_postProcessFieldArray($status, $table, $id, array &$fieldArray, DataHandler $dataHandler)
    {
        if ($table === 'tx_oauth2server_domain_model_client' && $status === 'new') {
            if (!isset($fieldArray['identifier'])) {
                $fieldArray['identifier'] = $this->random->generateRandomHexString(20);
            }

            if (!isset($fieldArray['secret'])) {
                $hashInstance = GeneralUtility::makeInstance(PasswordHashFactory::class)->getDefaultHashInstance('FE');
                $secret = base64_encode(random_bytes(32));
                $fieldArray['secret'] = $hashInstance->getHashedPassword($secret);
                $this->addFlashMessage(
                    LocalizationUtility::translate('LLL:EXT:oauth2_server/Resources/Private/Language/locallang_be.xlf:flash_message.client_secret', null, [$secret])
                );
            }
        }
    }

    protected function addFlashMessage($message, $title = '', $severity = FlashMessage::INFO)
    {
        $message = GeneralUtility::makeInstance(FlashMessage::class, $message, $title, $severity);

        $messageQueue = $this->flashMessageService->getMessageQueueByIdentifier();
        $messageQueue->addMessage($message);
    }
}
