<?php

namespace Zaimea\SDK\Groups\Actions;

use Zaimea\SDK\Groups\Resources\Group;

trait ManagesGroups
{
    /**
     * Get the collection of groups.
     *
     * @return \Zaimea\SDK\Groups\Resources\Group[]
     */
    public function groups()
    {
        return $this->transformCollection(
            $this->get("user/get/groups")['groups'],
            Group::class,
        );
    }

    /**
     * Get a group instance.
     *
     * @param  int  $groupId
     * @return \Zaimea\SDK\Groups\Resources\Group
     */
    public function group($groupId)
    {
        return new Group(
            $this->get("read", ['group' => $groupId])['data'], $this
        );
    }

    /**
     * Create a new group.
     *
     * @param  array $data
     * @param  bool  $wait
     * @return \Zaimea\SDK\Groups\Resources\Group
     */
    public function createGroup(array $data, $wait = true)
    {
        $group = $this->post("create", $data)['data'];

        if ($wait) {
            return $this->retry($this->getTimeout(), function () use ($group) {
                $group = $this->group($group['id']);

                return $group->status == 'created' ? $group : null;
            });
        }

        return new Group($group, $this);
    }

    /**
     * Delete the given group.
     *
     * @param  int  $groupId
     * @return void
     */
    public function deleteGroup($groupId)
    {
        $this->delete("delete", ['group' => $groupId]);
    }
}