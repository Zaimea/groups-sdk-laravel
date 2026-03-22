<?php

namespace Zaimea\SDK\Groups\Actions;

use Zaimea\SDK\Groups\Resources\Record;
use Zaimea\SDK\Groups\Resources\Response;

trait ManagesGroupVacations
{
    /**
     * Create a new group vacation record.
     *
     * @param  int  $groupId
     * @param  array  $data ['title' => '', 'notes' => '', 'dates' => '2026-03-21, 2026-03-22']
     * @return \Zaimea\SDK\Groups\Resources\Response
     */
    public function createVacation(int $groupId, array $data)
    {
        $params = array_merge(
            ['group' => $groupId],
            $data
        );
        
        return new Response($this->post("vacations/store", $params), $this);
    }

    /**
     * Get a group vacation record instance.
     *
     * @param  int  $groupId
     * @param  int  $vacationId
     * @return \Zaimea\SDK\Groups\Resources\Record
     */
    public function vacation(int $groupId, int $vacationId): Record
    {
        return new Record(
            $this->get("vacations/read", ['group' => $groupId, 'vacationId' =>  $vacationId])['data'], $this
        );
    }

    /**
     * Get paginated group vacation records.
     * @param  int  $groupId
     * @param  array  $filters ['search' => 'user id, date, title']
     * @param  int  $page
     * @return \Zaimea\SDK\Groups\Resources\Record[]
     */
    public function vacations($groupId, array $filters = [], int $page = 1)
    {
        $params = array_merge(
            ['group' => $groupId, 'page' => $page],
            $filters
        );

        return $this->transformCollection(
            $this->get("vacations/all", $params)['vacations'],
            Record::class,
            ['groupId' => $groupId]
        );
    }
    
    /**
     * Update the given group vacation record.
     *
     * @param  int  $groupId
     * @param  int  $vacationId
     * @param  string  $actionType  'approve' / 'disapprove'
     * @return \Zaimea\SDK\Groups\Resources\Response
     */
    public function updateVacation(int $groupId, int $vacationId, string $actionType)
    {
        return new Response($this->put("vacations/update", [
            'group' => $groupId, 
            'vacationId' => $vacationId,
            'actionType' => $actionType,
        ]), $this);
    }

    /**
     * Approve a vacation record.
     *
     * @param  int  $groupId
     * @param  int  $vacationId
     * @return \Zaimea\SDK\Groups\Resources\Response
     */
    public function approveVacation(int $groupId, int $vacationId)
    {
        return $this->updateVacation($groupId, $vacationId, 'approve');
    }

    /**
     * Disapprove a vacation record.
     *
     * @param  int  $groupId
     * @param  int  $vacationId
     * @return \Zaimea\SDK\Groups\Resources\Response
     */
    public function disapproveVacation(int $groupId, int $vacationId)
    {
        return $this->updateVacation($groupId, $vacationId, 'disapprove');
    }

    /**
     * Delete the given group vacation record.
     *
     * @param  int  $groupId
     * @param  int  $vacationId
     * @return \Zaimea\SDK\Groups\Resources\Response
     */
    public function deleteVacation(int $groupId, int $vacationId)
    {
        return new Response($this->delete("vacations/delete", [
            'group' => $groupId, 
            'vacationId' => $vacationId,
        ]), $this);
    }
}