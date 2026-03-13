<?php

declare(strict_types=1);

namespace Zaimea\SDK\Groups\Resources;

class Member extends Resource
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
    public function member(int $memberId)
    {
        $this->sdk->member($this->groupId, $memberId);
    }

    /**
     * Read the group members.
     *
     * @return void
     */
    public function members()
    {
        $this->sdk->members($this->groupId);
    }

    /**
     * Read the group members paginated.
     *
     * @param  array $filters
     * @param  int $page
     * @return void
     */
    public function membersPaginated(array $filters = [], int $page = 1)
    {
        $this->sdk->membersPaginated($this->groupId, $filters, $page);
    }
}