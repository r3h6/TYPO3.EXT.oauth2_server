<?php

declare(strict_types=1);

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

namespace R3H6\Oauth2Server\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class FrontendUserGroup extends AbstractEntity
{
    protected string $title = '';
    protected string $description = '';
    /** @var ObjectStorage<FrontendUserGroup> */
    protected ObjectStorage $subgroup;

    public function __construct()
    {
        $this->initializeObject();
    }

    public function initializeObject(): void
    {
        $this->subgroup = new ObjectStorage();
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param ObjectStorage<FrontendUserGroup> $subgroup
     */
    public function setSubgroup(ObjectStorage $subgroup): void
    {
        $this->subgroup = $subgroup;
    }

    /**
     * @return ObjectStorage<FrontendUserGroup>
     */
    public function getSubgroup(): ObjectStorage
    {
        return $this->subgroup;
    }
}
