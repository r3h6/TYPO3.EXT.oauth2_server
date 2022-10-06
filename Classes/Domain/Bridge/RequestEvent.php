<?php

declare(strict_types=1);
namespace R3H6\Oauth2Server\Domain\Bridge;

use League\OAuth2\Server\RequestEvent as OAuth2RequestEvent;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use TYPO3\CMS\Core\EventDispatcher\EventDispatcher;

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
 * Forwards events from PHP League's OAuth2 Server to TYPO3.
 */
final class RequestEvent implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @var EventDispatcher
     */
    private $eventDispatcher;

    public function __construct(EventDispatcher $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function __invoke(OAuth2RequestEvent $event)
    {
        $this->logger->debug('Forward event', ['name' => $event->getName()]);
        $this->eventDispatcher->dispatch($event);
    }
}
