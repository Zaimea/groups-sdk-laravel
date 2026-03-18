<?php

declare(strict_types=1);

namespace Zaimea\SDK\Groups\Resources;

use Illuminate\Support\Facades\Date;

class ClientMember extends Resource
{
    /**
     * The id of the group.
     *
     * @var int
     */
    public $groupId;

    /**
     * The id of the client user.
     *
     * @var int
     */
    public $userId;

    /**
     * The id of the client member.
     *
     * @var int
     */
    public $clientId;

    /**
     * The role of the client member.
     *
     * @var string
     */
    public $role;

    /**
     * Last time accessed.
     *
     * @var Date
     */
    public $accessed;

    /**
     * The status of the client member.
     *
     * @var int
     */
    public $status;
}