<?php

namespace Zaimea\SDK\Groups\Actions;

use Zaimea\SDK\Groups\Resources\Group;
use Zaimea\SDK\Groups\Resources\Record;

trait ManagesClients
{
    /**
     * Get paginated group client records.
     * 
     * @param  array  $filters [
     *                           'search' => 'user id, scheduled or title', 'users' => ['',''],
     *                           'groups' => ['',''], 'start' => '', 'end' => ''
     *                         ]
     * @param  int  $page
     * @return \Zaimea\SDK\Groups\Resources\Member[]
     */
    public function clientRecords(array $filters = [], int $page = 1)
    {
        $params = array_merge(
            [ 'page' => $page],
            $filters
        );

        return $this->transformCollection(
            $this->get("client/get-records", $params)['records'],
            Record::class,
        );
    }

    /**
     * Get client groups.
     *
     * @return \Zaimea\SDK\Groups\Resources\Group[]
     */
    public function clientGroups()
    {
        return $this->transformCollection(
            $this->get("client/get-groups")['groups'],
            Group::class,
        );
    }
}