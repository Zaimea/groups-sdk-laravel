<?php

declare(strict_types=1);

namespace Zaimea\SDK\Groups\Actions;

use Zaimea\SDK\Groups\Resources\Record;
use Zaimea\SDK\Groups\Resources\Response;

trait ManagesGroupMedicals
{
    /**
     * Create a new group medical record.
     *
     * @param  int  $groupId
     * @param  array  $data ['title' => '', 'notes' => '', 'dates' => '2026-03-21, 2026-03-22']
     * @return \Zaimea\SDK\Groups\Resources\Response
     */
    public function createMedical(int $groupId, array $data)
    {
        $params = array_merge(
            ['group' => $groupId],
            $data
        );
        
        return new Response($this->post("medicals/store", $params), $this);
    }

    /**
     * Get a group medical record instance.
     *
     * @param  int  $groupId
     * @param  int  $medicalId
     * @return \Zaimea\SDK\Groups\Resources\Record
     */
    public function medical(int $groupId, int $medicalId): Record
    {
        return new Record(
            $this->get("medicals/read", ['group' => $groupId, 'medicalId' =>  $medicalId])['data'], $this
        );
    }

    /**
     * Get paginated group medical records.
     * @param  int  $groupId
     * @param  array  $filters ['search' => 'user id / date / title']
     * @param  int  $page
     * @return \Zaimea\SDK\Groups\Resources\Record[]
     */
    public function medicals($groupId, array $filters = [], int $page = 1)
    {
        $params = array_merge(
            ['group' => $groupId, 'page' => $page],
            $filters
        );

        return $this->transformCollection(
            $this->get("medicals/all", $params)['medicals'],
            Record::class,
            ['groupId' => $groupId]
        );
    }
    
    /**
     * Update the given group medical record.
     *
     * @param  int  $groupId
     * @param  int  $medicalId
     * @param  string  $actionType  'approve' / 'disapprove'
     * @return \Zaimea\SDK\Groups\Resources\Response
     */
    public function updateMedical(int $groupId, int $medicalId, string $actionType)
    {
        return new Response($this->put("medicals/update", [
            'group' => $groupId, 
            'medicalId' => $medicalId,
            'actionType' => $actionType,
        ]), $this);
    }

    /**
     * Approve a medical record.
     *
     * @param  int  $groupId
     * @param  int  $medicalId
     * @return mixed
     */
    public function approveMedical(int $groupId, int $medicalId)
    {
        return $this->updateMedical($groupId, $medicalId, 'approve');
    }

    /**
     * Disapprove a medical record.
     *
     * @param  int  $groupId
     * @param  int  $medicalId
     * @return mixed
     */
    public function disapproveMedical(int $groupId, int $medicalId)
    {
        return $this->updateMedical($groupId, $medicalId, 'disapprove');
    }

    /**
     * Delete the given group medical record.
     *
     * @param  int  $groupId
     * @param  int  $medicalId
     * @return \Zaimea\SDK\Groups\Resources\Response
     */
    public function deleteMedical(int $groupId, int $medicalId)
    {
        return new Response($this->delete("medicals/delete", [
            'group' => $groupId, 
            'medicalId' => $medicalId,
        ]), $this);
    }
}