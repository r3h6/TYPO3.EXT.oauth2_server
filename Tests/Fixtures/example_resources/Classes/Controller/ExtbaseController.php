<?php

declare(strict_types=1);

namespace R3H6\ExampleResources\Controller;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class ExtbaseController extends ActionController
{
    public function indexAction(): ResponseInterface
    {
        return $this->jsonResponse((string)json_encode([
            'message' => 'Hello World!',
        ]));
    }

}
