<?php

declare(strict_types=1);

namespace Zaimea\SDK\Groups\Actions;

use Zaimea\SDK\Groups\Resources\Member;
use Zaimea\SDK\Groups\Resources\Response;
use Zaimea\SDK\Groups\Resources\Role;

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
     * Get paginated group members.
     *
     * @param  int  $groupId
     * @param  array  $filters ['search' => 'user id ']
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
     * @param  array  $data [
     *                          'email' => 'laurentiu@custura.de',
     *                          'role' => 'member',
     *                          'rate' => '07:00:00',
     *                          'quota_percent' => 100,
     *                          'working_days' => [
     *                              'monday' => true,
     *                              'tuesday' => true,
     *                              'wednesday' => true,
     *                              'thursday' => true,
     *                              'friday' => true,
     *                              'saturday' => false,
     *                              'sunday' => false,
     *                          ],
     *                          'status' => 1
     *                      ]
     * @return \Zaimea\SDK\Groups\Resources\Response
     */
    public function createMember(int $groupId, array $data)
    {
        $params = array_merge(
            ['group' => $groupId],
            $data
        );
        
        return new Response($this->post("members/create", $params), $this);
    }
    
    /**
     * Update the given group member.
     * 
     * @param  int  $groupId
     * @param  array  $data ['memberId' => 1, 'rate' => '07:00:00', 'quota_percent' => 100, 
     *                          'working_days' => [
     *                              'monday' => true,
     *                              'tuesday' => true,
     *                              'wednesday' => true,
     *                              'thursday' => true,
     *                              'friday' => true,
     *                              'saturday' => false,
     *                              'sunday' => false,
     *                          ], 
     *                          'status' => ''
     *                      ]
     * @return \Zaimea\SDK\Groups\Resources\Response
     */
    public function updateMember(int $groupId, array $data)
    {
        $params = array_merge(
            ['group' => $groupId],
            $data
        );

        return new Response($this->put("members/update", $params), $this);
    }
    
    /**
     * Update the given group member role.
     * 
     * @param  int  $groupId
     * @param  array  $data ['memberId' => 1, 'role' => 'member']
     * @return \Zaimea\SDK\Groups\Resources\Response
     */
    public function updateMemberRole(int $groupId, array $data)
    {
        $params = array_merge(
            ['group' => $groupId],
            $data
        );

        return new Response($this->put("members/update/role", $params), $this);
    }

    /**
     * Delete the given group member.
     *
     * @param  int  $groupId
     * @param  int  $memberId
     * @return \Zaimea\SDK\Groups\Resources\Response
     */
    public function deleteMember(int $groupId, int $memberId)
    {
        return new Response($this->delete("members/delete", [
            'group' => $groupId, 
            'memberId' => $memberId,
        ]), $this);
    }

    /**
     * Get paginated group member roles.
     *
     * @param  int  $groupId
     * @param  array  $filters ['search' => 'key / name / permissions / description / created_at']
     * @param  int  $page
     * @return \Zaimea\SDK\Groups\Resources\Role[]
     */
    public function memberRoles($groupId, array $filters = [], int $page = 1)
    {
        $params = array_merge(
            ['group' => $groupId, 'page' => $page],
            $filters
        );

        return $this->transformCollection(
            $this->get("members/roles", $params)['data'],
            Role::class,
            ['groupId' => $groupId]
        );
    }
}