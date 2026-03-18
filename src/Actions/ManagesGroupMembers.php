<?php

namespace Zaimea\SDK\Groups\Actions;

use Zaimea\SDK\Groups\Resources\Member;
use Zaimea\SDK\Groups\Resources\Response;

trait ManagesGroupMembers
{
    /**
     * Get a group member instance.
     *
     * @param  int  $groupId
     * @param  int  $memberId
     * @return \Zaimea\SDK\Groups\Resources\Member
     */
    public function member(int $groupId, int $memberId): Member
    {
        return new Member(
            $this->get("members/read", ['group' => $groupId, 'memberId' =>  $memberId])['data'], $this
        );
    }

    /**
     * Get a group members instance.
     *
     * @param  int  $groupId
     * @return \Zaimea\SDK\Groups\Resources\Member[]
     */
    public function membersAll(int $groupId)
    {
        return $this->transformCollection(
            $this->get("members/all", ['group' => $groupId])['members'],
            Member::class,
        );
    }

    /**
     * Get paginated group members.
     *
     * @param  int  $groupId
     * @param  array  $filters
     * @param  int  $page
     * @return \Zaimea\SDK\Groups\Resources\Member[]
     */
    public function members($groupId, array $filters = [], int $page = 1)
    {
        $params = array_merge(
            ['group' => $groupId, 'page' => $page],
            $filters
        );

        return $this->transformCollection(
            $this->get("members/all", $params)['members'],
            Member::class,
            ['groupId' => $groupId]
        );
    }

    /**
     * Create a new group member.
     *
     * @param  int  $groupId
     * @param  array  $data
     * @return \Zaimea\SDK\Groups\Resources\Response
     */
    public function createGroupMember(int $groupId, array $data)
    {
        $params = array_merge(
            ['group' => $groupId],
            $data
        );
        
        return new Response($this->post("members/create", $params), $this);
    }
}