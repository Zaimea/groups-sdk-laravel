<?php

namespace Zaimea\SDK\Groups\Actions;

use Zaimea\SDK\Groups\Resources\Record;
use Zaimea\SDK\Groups\Resources\Response;

trait ManagesGroupRecords
{
    /**
     * Get a group record instance.
     *
     * @param  int  $groupId
     * @param  int  $recordId
     * @return \Zaimea\SDK\Groups\Resources\Record
     */
    public function record(int $groupId, int $recordId): Record
    {
        return new Record(
            $this->get("records/read", ['group' => $groupId, 'recordId' =>  $recordId])['data'], $this
        );
    }

    /**
     * Get all records for a group.
     *
     * @param  int  $groupId
     * @return \Zaimea\SDK\Groups\Resources\Record[]
     */
    public function recordsAll(int $groupId)
    {
        return $this->transformCollection(
            $this->get("records", ['group' => $groupId])['records']['data'],
            Record::class,
            ['groupId' => $groupId]
        );
    }

    /**
     * Get paginated group records.
     * @param  int  $groupId
     * @param  array  $filters
     * @param  int  $page
     * @return \Zaimea\SDK\Groups\Resources\Member[]
     */
    public function records($groupId, array $filters = [], int $page = 1)
    {
        $params = array_merge(
            ['group' => $groupId, 'page' => $page],
            $filters
        );

        return $this->transformCollection(
            $this->get("records/all", $params)['records'],
            Record::class,
            ['groupId' => $groupId]
        );
    }

    /**
     * Get records aggregate for a group.
     *
     * @param  int  $groupId
     * @param  array  $filters
     * @return \Zaimea\SDK\Groups\Resources\Record[]
     */
    public function recordsAggregate(int $groupId, array $filters = [])
    {
        $params = array_merge(['group' => $groupId], $filters);
    
        $aggregates = $this->get("records-aggregate", $params);
        
        $works = $this->transformCollection(
            $aggregates['works']['data'],
            Record::class,
            ['groupId' => $groupId]
        );
        $holidays = $this->transformCollection(
            $aggregates['holidays']['data'],
            Record::class,
            ['groupId' => $groupId]
        );
        $vacations = $this->transformCollection(
            $aggregates['vacations']['data'],
            Record::class,
            ['groupId' => $groupId]
        );
        $medicals = $this->transformCollection(
            $aggregates['medicals']['data'],
            Record::class,
            ['groupId' => $groupId]
        );

        return [
            'works' => $works,
            'holidays' => $holidays,
            'vacations' => $vacations,
            'medicals' => $medicals,
        ];
    }
    
    /**
     * Update the given group record.
     *
     * @param  int  $groupId
     * @param  int  $recordId
     * @param  string  $actionType  'approve' / 'disapprove'
     * @return \Zaimea\SDK\Groups\Resources\Response
     */
    public function updateGroupRecord(int $groupId, int $recordId, string $actionType)
    {
        return new Response($this->put("records/update", [
            'group' => $groupId, 
            'recordId' => $recordId,
            'actionType' => $actionType,
        ]), $this);
    }

    /**
     * Approve a record.
     *
     * @param  int  $groupId
     * @param  int  $recordId
     * @return mixed
     */
    public function approveGroupRecord(int $groupId, int $recordId)
    {
        return $this->updateGroupRecord($groupId, $recordId, 'approve');
    }

    /**
     * Disapprove a record.
     *
     * @param  int  $groupId
     * @param  int  $recordId
     * @return mixed
     */
    public function disapproveGroupRecord(int $groupId, int $recordId)
    {
        return $this->updateGroupRecord($groupId, $recordId, 'disapprove');
    }

    /**
     * Delete the given group record.
     *
     * @param  int  $groupId
     * @param  int  $recordId
     * @return \Zaimea\SDK\Groups\Resources\Response
     */
    public function deleteGroupRecord(int $groupId, int $recordId)
    {
        return new Response($this->delete("records/delete", [
            'group' => $groupId, 
            'recordId' => $recordId,
        ]), $this);
    }
}