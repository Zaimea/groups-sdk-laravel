<?php

declare(strict_types=1);

namespace Zaimea\SDK\Groups\Resources;

use Zaimea\SDK\Groups\Resources\Resource;

class User extends Resource
{
    /**
     * The id of the user.
     *
     * @var int
     */
    public $id;

    /**
     * The name of the user.
     *
     * @var string
     */
    public $name;

    /**
     * The E-Mail of the user.
     *
     * @var string
     */
    public $email;

    /**
     * Determines if user can create groups.
     *
     * @var bool
     */
    public $canCreateGroups;
}