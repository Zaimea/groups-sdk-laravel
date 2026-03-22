<?php

declare(strict_types=1);

namespace Zaimea\SDK\Groups\Actions;

use Zaimea\SDK\Groups\Resources\Count;

trait ManagesGroupCounts
{
    /**
     * Get Employees counted.
     *
     * @return \Zaimea\SDK\Groups\Resources\Count
     */
    public function countEmployees()
    {
        return new Count(
            $this->get("count/employees"), $this
        );
    }

    /**
     * Get Groups counted.
     *
     * @return \Zaimea\SDK\Groups\Resources\Count
     */
    public function countGroups()
    {
        return new Count(
            $this->get("count/groups"), $this
        );
    }
    
    /**
     * Get Hours counted.
     *
     * @return \Zaimea\SDK\Groups\Resources\Count
     */
    public function countHours()
    {
        return new Count(
            $this->get("count/hours"), $this
        );
    }
}