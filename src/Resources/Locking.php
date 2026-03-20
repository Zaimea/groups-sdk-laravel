<?php

declare(strict_types=1);

namespace Zaimea\SDK\Groups\Resources;

class Locking extends Resource
{
    /**
     * The id of the group.
     *
     * @var int
     */
    public $groupId;

    /**
     * Get a specific locking record from the group.
     *
     * @param int $lockingId
     * @return void
     */
    public function locking(int $lockingId)
    {
        $this->sdk->locking($this->groupId, $lockingId);
    }

    /**
     * Read the group lockings record paginated.
     *
     * @param array $filters
     * @param int $page
     * @return void
     */
    public function lockings(array $filters = [], int $page = 1)
    {
        $this->sdk->lockings($this->groupId, $filters, $page);
    }

    /**
     * Delete the given group locking.
     *
     * @param  int  $lockingId
     * @return void
     */
    public function deleteGroupLocking(int $lockingId)
    {
        $this->sdk->deleteGroupLocking($this->groupId, $lockingId);
    }
}