<?php

declare(strict_types=1);

namespace Zaimea\SDK\Groups\Actions;

use Zaimea\SDK\Groups\Resources\Locking;
use Zaimea\SDK\Groups\Resources\Response;

trait ManagesGroupLockings
{
    /**
     * Create a new group locking.
     *
     * @param  int  $groupId
     * @param  array  $data ['param' => '0 * * * *']
     * @return \Zaimea\SDK\Groups\Resources\Response
     */
    public function createLocking(int $groupId, array $data)
    {
        $params = array_merge(
            ['group' => $groupId],
            $data
        );
        
        return new Response($this->post("lockings/store", $params), $this);
    }

    /**
     * Update the given group locking.
     *
     * @param  int  $groupId
     * @param  array  $data ['lockingId' => 1, 'param' => '0 * * * *']
     * @return \Zaimea\SDK\Groups\Resources\Response
     */
    public function updateLocking(int $groupId, array $data)
    {
        $params = array_merge(
            ['group' => $groupId],
            $data
        );

        return new Response($this->put("lockings/update", $params), $this);
    }

    /**
     * Get a group locking instance.
     *
     * @param  int  $groupId
     * @param  int  $lockingId
     * @return \Zaimea\SDK\Groups\Resources\Locking
     */
    public function locking(int $groupId, int $lockingId): Locking
    {
        return new Locking(
            $this->get("lockings/read", ['group' => $groupId, 'lockingId' =>  $lockingId])['data'], $this
        );
    }

    /**
     * Get paginated group lockings.
     * @param  int  $groupId
     * @param  int  $page
     * @return \Zaimea\SDK\Groups\Resources\Locking[]
     */
    public function lockings($groupId, int $page = 1)
    {
        $params = array_merge(
            ['group' => $groupId, 'page' => $page]
        );

        return $this->transformCollection(
            $this->get("lockings/all", $params)['data'],
            Locking::class,
            ['groupId' => $groupId]
        );
    }

    /**
     * Delete the given group locking.
     *
     * @param  int  $groupId
     * @param  int  $lockingId
     * @return \Zaimea\SDK\Groups\Resources\Response
     */
    public function deleteLocking(int $groupId, int $lockingId)
    {
        return new Response($this->delete("lockings/delete", [
            'group' => $groupId, 
            'lockingId' => $lockingId,
        ]), $this);
    }
}