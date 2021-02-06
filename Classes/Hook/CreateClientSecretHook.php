<?php

namespace R3H6\Oauth2Server\Hook;

use TYPO3\CMS\Core\Crypto\Random;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Messaging\FlashMessageService;
use TYPO3\CMS\Core\Crypto\PasswordHashing\PasswordHashFactory;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

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
            $secret = base64_encode(random_bytes(32));

            $hashInstance = GeneralUtility::makeInstance(PasswordHashFactory::class)->getDefaultHashInstance('FE');
            $hashedSecret = $hashInstance->getHashedPassword($secret);

            $identifier = $this->random->generateRandomHexString(20);

            $fieldArray['identifier'] = $identifier;
            $fieldArray['secret'] = $hashedSecret;

            $this->addFlashMessage(
                LocalizationUtility::translate('LLL:EXT:oauth2_server/Resources/Private/Language/locallang_be.xlf:flash_message.client_secret', null, [$secret])
            );
        }
    }

    protected function addFlashMessage($message, $title = '', $severity = FlashMessage::INFO)
    {
        $message = GeneralUtility::makeInstance(FlashMessage::class, $message, $title, $severity);

        $messageQueue = $this->flashMessageService->getMessageQueueByIdentifier();
        $messageQueue->addMessage($message);
    }

}
