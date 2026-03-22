<?php

declare(strict_types=1);

namespace Zaimea\SDK\Groups\Actions;

use Zaimea\SDK\Groups\Resources\Holiday;
use Zaimea\SDK\Groups\Resources\Record;
use Zaimea\SDK\Groups\Resources\Response;

trait ManagesGroupHolidays
{
    /**
     * Create a new group holiday.
     *
     * @param  int  $groupId
     * @param  array  $data ['title' => '', 'description' => 'red', 'users' => ['1'], 'dates' => '2026-03-20, 2026-03-21']
     * @return \Zaimea\SDK\Groups\Resources\Response
     */
    public function createHoliday(int $groupId, array $data)
    {
        $params = array_merge(
            ['group' => $groupId],
            $data
        );
        
        return new Response($this->post("holidays/store", $params), $this);
    }

    /**
     * Get a group holiday instance.
     *
     * @param  int  $groupId
     * @param  int  $holidayId
     * @return \Zaimea\SDK\Groups\Resources\Holiday
     */
    public function holiday(int $groupId, int $holidayId): Holiday
    {
        return new Holiday(
            $this->get("holidays/read", ['group' => $groupId, 'holidayId' =>  $holidayId])['data'], $this
        );
    }

    /**
     * Get paginated group holidays.
     * @param  int  $groupId
     * @param  array  $filters ['search' => 'user id']
     * @param  int  $page
     * @return \Zaimea\SDK\Groups\Resources\Holiday[]
     */
    public function holidays($groupId, array $filters = [], int $page = 1)
    {
        $params = array_merge(
            ['group' => $groupId, 'page' => $page],
            $filters
        );

        return $this->transformCollection(
            $this->get("holidays/all", $params)['holidays'],
            Holiday::class,
            ['groupId' => $groupId]
        );
    }

    /**
     * Delete the given group holiday.
     *
     * @param  int  $groupId
     * @param  int  $recordId
     * @return \Zaimea\SDK\Groups\Resources\Response
     */
    public function deleteGroupHoliday(int $groupId, int $recordId)
    {
        return new Response($this->delete("holidays/delete", [
            'group' => $groupId, 
            'recordId' => $recordId,
        ]), $this);
    }

    /**
     * Get a group member holiday instance.
     *
     * @param  int  $groupId
     * @param  int  $holidayId
     * @return \Zaimea\SDK\Groups\Resources\Record
     */
    public function memberHoliday(int $groupId, int $holidayId): Record
    {
        return new Record(
            $this->get("holidays/read/member", ['group' => $groupId, 'holidayId' =>  $holidayId])['data'], $this
        );
    }
    
    /**
     * Get paginated group member holidays records.
     * @param  int  $groupId
     * @param  array  $filters ['search' => 'user id']
     * @param  int  $page
     * @return \Zaimea\SDK\Groups\Resources\Record[]
     */
    public function memberHolidays($groupId, array $filters = [], int $page = 1)
    {
        $params = array_merge(
            ['group' => $groupId, 'page' => $page],
            $filters
        );

        return $this->transformCollection(
            $this->get("holidays/member", $params)['holidays'],
            Record::class,
            ['groupId' => $groupId]
        );
    }

    /**
     * Delete the given group member a holiday record.
     *
     * @param  int  $groupId
     * @param  int  $recordId
     * @return \Zaimea\SDK\Groups\Resources\Response
     */
    public function deleteHolidayMember(int $groupId, int $recordId)
    {
        return new Response($this->delete("holidays/delete/member", [
            'group' => $groupId, 
            'recordId' => $recordId,
        ]), $this);
    }
}