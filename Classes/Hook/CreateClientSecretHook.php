<?php

declare(strict_types=1);
namespace R3H6\Oauth2Server\Hook;

use TYPO3\CMS\Core\Crypto\PasswordHashing\PasswordHashFactory;
use TYPO3\CMS\Core\Crypto\Random;
use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Messaging\FlashMessageService;
use TYPO3\CMS\Core\Type\ContextualFeedbackSeverity;
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
     * @var FlashMessageService
     */
    protected $flashMessageService;

    /**
     * @var Random
     */
    protected $random;

    public function __construct(FlashMessageService $flashMessageService, Random $random)
    {
        $this->flashMessageService = $flashMessageService;
        $this->random = $random;
    }

    /**
     * @param string|int $id
     */
    public function processDatamap_postProcessFieldArray(string $status, string $table, $id, array &$fieldArray, DataHandler $dataHandler): void
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

    protected function addFlashMessage(string $message, string $title = '', ContextualFeedbackSeverity $severity = ContextualFeedbackSeverity::INFO): void
    {
        $flashMessage = GeneralUtility::makeInstance(FlashMessage::class, $message, $title, $severity, true);

        $messageQueue = $this->flashMessageService->getMessageQueueByIdentifier();
        $messageQueue->addMessage($flashMessage);
    }
}
