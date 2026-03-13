<?php

declare(strict_types=1);

namespace Zaimea\SDK\Groups\Resources;

class Record extends Resource
{
    /**
     * The id of the group.
     *
     * @var int
     */
    public $groupId;

    /**
     * Get a specific record from the group.
     *
     * @param int $recordId
     * @return void
     */
    public function record(int $recordId)
    {
        $this->sdk->record($this->groupId, $recordId);
    }

    /**
     * Read the group all records.
     *
     * @return void
     */
    public function records()
    {
        $this->sdk->records($this->groupId);
    }

    /**
     * Read the group all records paginated.
     *
     * @param array $filters
     * @param int $page
     * @return void
     */
    public function recordsPaginated(array $filters = [], int $page = 1)
    {
        $this->sdk->recordsPaginated($this->groupId, $filters, $page);
    }

    /**
     * Delete the given group record.
     *
     * @param  int  $recordId
     * @return void
     */
    public function delete(int $recordId)
    {
        $this->sdk->deleteGroupRecord($this->groupId, $recordId);
    }
}