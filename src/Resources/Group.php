<?php

declare(strict_types=1);

namespace Zaimea\SDK\Groups\Resources;

class Group extends Resource
{
    /**
     * The id of the group.
     *
     * @var int
     */
    public $groupId;

    /**
     * The status of the group.
     *
     * @var string
     */
    public $status;

    /**
     * The date/time the group was created.
     *
     * @var string
     */
    public $createdAt;

    /**
     * Read the given group.
     *
     * @return void
     */
    public function group()
    {
        $this->sdk->group($this->groupId);
    }

    /**
     * Read the groups.
     *
     * @return void
     */
    public function groups()
    {
        $this->sdk->groups();
    }

    /**
     * Mount data for given group.
     *
     * @return void
     */
    public function mountGroup()
    {
        $this->sdk->mountGroup($this->groupId);
    }

    /**
     * Set user current group.
     *
     * @return void
     */
    public function setCurrentGroup()
    {
        $this->sdk->setCurrentGroup($this->groupId);
    }

    /**
     * Delete the given group.
     *
     * @return void
     */
    public function delete()
    {
        $this->sdk->deleteGroup($this->groupId);
    }
}