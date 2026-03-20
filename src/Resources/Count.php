<?php

declare(strict_types=1);

namespace Zaimea\SDK\Groups\Resources;

class Count extends Resource
{
    /**
     * Get Employees counted.
     *
     * @return void
     */
    public function countEmployees()
    {
        $this->sdk->countEmployees();
    }

    /**
     * Get Groups counted.
     *
     * @return void
     */
    public function countGroups()
    {
        $this->sdk->countGroups();
    }

    /**
     * Get Hours counted.
     *
     * @return void
     */
    public function countHours()
    {
        $this->sdk->countHours();
    }
}