<?php

declare(strict_types=1);

namespace Zaimea\SDK\Groups\Resources;

class Project extends Resource
{
    /**
     * The id of the group.
     *
     * @var int
     */
    public $groupId;

    /**
     * The id of the group client.
     *
     * @var int
     */
    public $clientId;

    /**
     * The id of the group client project.
     *
     * @var int
     */
    public $projectId;
}