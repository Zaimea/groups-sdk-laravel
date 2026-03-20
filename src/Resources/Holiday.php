<?php

declare(strict_types=1);

namespace Zaimea\SDK\Groups\Resources;

class Holiday extends Resource
{
    /**
     * The id of the group.
     *
     * @var int
     */
    public $groupId;

    /**
     * Get a specific holiday record from the group.
     *
     * @param int $recordId
     * @return void
     */
    public function holiday(int $recordId)
    {
        $this->sdk->holiday($this->groupId, $recordId);
    }

    /**
     * Read the group holidays record paginated.
     *
     * @param array $filters
     * @param int $page
     * @return void
     */
    public function holidays(array $filters = [], int $page = 1)
    {
        $this->sdk->holidays($this->groupId, $filters, $page);
    }

    /**
     * Delete the given group holiday.
     *
     * @param  int  $recordId
     * @return void
     */
    public function deleteGroupHoliday(int $recordId)
    {
        $this->sdk->deleteGroupHoliday($this->groupId, $recordId);
    }
}