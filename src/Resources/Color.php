<?php

declare(strict_types=1);

namespace Zaimea\SDK\Groups\Resources;

class Color extends Resource
{
    /**
     * The id of the group.
     *
     * @var int
     */
    public $groupId;

    /**
     * Get a specific color from the group.
     *
     * @param int $colorId
     * @return void
     */
    public function color(int $colorId)
    {
        $this->sdk->color($this->groupId, $colorId);
    }

    /**
     * Read the group colors.
     *
     * @return void
     */
    public function colors()
    {
        $this->sdk->colors($this->groupId);
    }

    /**
     * Delete the given group color.
     *
     * @param  int  $colorId
     * @return void
     */
    public function delete(int $colorId)
    {
        $this->sdk->deleteColor($this->groupId, $colorId);
    }
}