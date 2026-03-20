<?php

namespace Zaimea\SDK\Groups\Actions;

use Zaimea\SDK\Groups\Resources\Group;
use Zaimea\SDK\Groups\Resources\GroupMount;
use Zaimea\SDK\Groups\Resources\Response;

trait ManagesGroups
{
    /**
     * Get a group instance.
     *
     * @param  int  $groupId
     * @return \Zaimea\SDK\Groups\Resources\Group
     */
    public function group($groupId)
    {
        return new Group(
            $this->get("group/read", ['group' => $groupId])['data']
            + ['groupId' => $groupId], $this
        );
    }

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
     * Get a group data.
     *
     * @param  int  $groupId
     * @return \Zaimea\SDK\Groups\Resources\GroupMount
     */
    public function mountGroup($groupId)
    {
        return new GroupMount(
            $this->get("group/mount", ['group' => $groupId])['data']
            + ['groupId' => $groupId], $this
        );
    }

    /**
     * Set user the current group.
     *
     * @param  int  $groupId
     * @return \Zaimea\SDK\Groups\Resources\Response
     */
    public function setCurrentGroup($groupId)
    {
        return new Response(
            $this->put("current-group", ['group' => $groupId]), $this
        );
    }

    /**
     * Create a new group.
     *
     * @param  array $data ['name' => '']
     * @param  bool  $wait
     * @return \Zaimea\SDK\Groups\Resources\Group
     */
    public function createGroup(array $data, $wait = true)
    {
        $group = $this->post("group/create", $data)['group'];

        if ($wait) {
            return $this->retry($this->getTimeout(), function () use ($group) {
                $group = $this->group($group['id']);

                return $group->status == 1 ? $group : null;
            });
        }

        return new Group($group, $this);
    }

    /**
     * Update a group.
     *
     * @param  int   $groupId
     * @param  array $data ['name'=> '', 'description' => '']
     * @return \Zaimea\SDK\Groups\Resources\Response
     */
    public function updateGroup(int $groupId, array $data)
    {
        $params = array_merge(
            ['group' => $groupId],
            $data,
        );

        return new Response(
            $this->put("group/update", $params)
            + ['groupId' => $groupId], $this
        );
    }

    /**
     * Update a group details.
     * 
     * @param  int   $groupId
     * @param  array $data ['phone'=> '', 'adress' => '', 'zip' => '', 'city' => '', 'country' => '']
     * @return \Zaimea\SDK\Groups\Resources\Response
     */
    public function updateGroupDetails(int $groupId, array $data)
    {
        $params = array_merge(
            ['group' => $groupId],
            $data,
        );

        return new Response(
            $this->put("group/update/details", $params)
            + ['groupId' => $groupId], $this
        );
    }

    /**
     * Update a group settings.
     * 
     * @param  int   $groupId
     * @param  array $data ['lang'=> '', 'time_format' => '', 'date_format' => '', 'week_start' => '',
     *                      'tracking_mode' => '', 'record_type' => '', 'template_sign' => '',
     *                      'pluginsSelected' => '', 'configsSelected' => '', 'projectRequired' => '']
     * @param  bool  $wait
     * @return \Zaimea\SDK\Groups\Resources\Response
     */
    public function updateGroupSettings(int $groupId, array $data)
    {
        $params = array_merge(
            ['group' => $groupId],
            $data,
        );
        
        return new Response(
            $this->put("group/update/settings", $params)
            + ['groupId' => $groupId], $this
        );
    }

    /**
     * Transfer a group.
     * 
     * @param  int   $groupId
     * @param  array $data ['password'=> '', 'email' => '',]
     * @return \Zaimea\SDK\Groups\Resources\Response
     */
    public function transferGroup(int $groupId, array $data)
    {
        $params = array_merge(
            ['group' => $groupId],
            $data,
        );
        
        return new Response(
            $this->put("group/transfer", $params)
            + ['groupId' => $groupId], $this
        );
    }

    /**
     * Delete the given group.
     *
     * @param  int  $groupId
     * @return \Zaimea\SDK\Groups\Resources\Response
     */
    public function deleteGroup($groupId)
    {
        return new Response(
            $this->delete("group/delete", ['group' => $groupId]), $this
        );
    }
}