<?php

declare(strict_types=1);

namespace Zaimea\SDK\Groups\Resources;

class Client extends Resource
{
    /**
     * The id of the group.
     *
     * @var int
     */
    public $groupId;

    /**
     * Read the group member.
     *
     * @param  int $memberId
     * @return void
     */
    public function client(int $memberId)
    {
        $this->sdk->member($this->groupId, $memberId);
    }

    /**
     * Read the group members.
     *
     * @return void
     */
    public function clientsAll()
    {
        $this->sdk->membersAll($this->groupId);
    }

    /**
     * Read paginated group members.
     *
     * @param  array $filters
     * @param  int $page
     * @return void
     */
    public function clients(array $filters = [], int $page = 1)
    {
        $this->sdk->members($this->groupId, $filters, $page);
    }
}