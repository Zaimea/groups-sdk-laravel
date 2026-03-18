<?php

namespace Zaimea\SDK\Groups\Actions;

use Zaimea\SDK\Groups\Resources\Client;
use Zaimea\SDK\Groups\Resources\ClientMember;
use Zaimea\SDK\Groups\Resources\Project;
use Zaimea\SDK\Groups\Resources\Response;
use Zaimea\SDK\Groups\Resources\Role;

trait ManagesGroupClients
{
    /**
     * Get a group client instance.
     *
     * @param  int  $groupId
     * @param  int  $clientId
     * @return \Zaimea\SDK\Groups\Resources\Client
     */
    public function client(int $groupId, int $clientId): Client
    {
        return new Client(
            $this->get("clients/get", ['group' => $groupId, 'clientId' =>  $clientId])['data'], $this
        );
    }

    /**
     * Get paginated group clients.
     *
     * @param  int  $groupId
     * @param  array  $filters ['search' => 'client name']
     * @param  int  $page
     * @return \Zaimea\SDK\Groups\Resources\Client[]
     */
    public function clients($groupId, array $filters = [], int $page = 1)
    {
        $params = array_merge(
            ['group' => $groupId, 'page' => $page],
            $filters
        );

        return $this->transformCollection(
            $this->get("clients", $params)['clients'],
            Client::class,
            ['groupId' => $groupId]
        );
    }

    /**
     * Get paginated group client members.
     *
     * @param  int  $groupId
     * @param  array  $filters ['search' => 'client id']
     * @param  int  $page
     * @return \Zaimea\SDK\Groups\Resources\ClientMember[]
     */
    public function clientMembers($groupId, array $filters = [], int $page = 1)
    {
        $params = array_merge(
            ['group' => $groupId, 'page' => $page],
            $filters
        );

        return $this->transformCollection(
            $this->get("clients/members", $params)['members'],
            ClientMember::class,
        );
    }

    /**
     * Create a new client group member.
     * 
     * @param  int  $groupId
     * @param  array  $data ['email' => '', 'role' => '', 'clientId' => int, 'status' => int]
     * @return \Zaimea\SDK\Groups\Resources\Response
     */
    public function createGroupClientMember(int $groupId, array $data)
    {
        $params = array_merge(
            ['group' => $groupId],
            $data
        );
        
        return new Response($this->post("clients/add/member", $params), $this);
    }

    /**
     * Create a new client group.
     * 
     * @param  int  $groupId
     * @param  array  $data ['client_name' => '', 'client_adress' => '', 'projectBinds' => [1,2,3], 'status' => 1]
     * @return \Zaimea\SDK\Groups\Resources\Response
     */
    public function createGroupClient(int $groupId, array $data)
    {
        $params = array_merge(
            ['group' => $groupId],
            $data
        );
        
        return new Response($this->post("clients/store", $params), $this);
    }
    
    /**
     * Update group client data.
     * 
     * @param  int  $groupId
     * @param  int  $clientId
     * @param  array  $data ['client_name' => '', 'client_adress' => '', 'projects' => '1,2', 'status' => 1]
     * @return \Zaimea\SDK\Groups\Resources\Response
     */
    public function updateGroupClient(int $groupId, int $clientId, array $data)
    {
        $params = array_merge(
            ['group' => $groupId, 'clientId' => $clientId],
            $data,
        );

        return new Response($this->put("clients/update", $params), $this);
    }
    
    /**
     * Update group client member data.
     * 
     * @param  int  $groupId
     * @param  array  $data ['memberId' => '', 'clientId' => '', 'status' => 1]
     * @return \Zaimea\SDK\Groups\Resources\Response
     */
    public function updateGroupClientMember(int $groupId, array $data)
    {
        $params = array_merge(
            ['group' => $groupId],
            $data,
        );

        return new Response($this->put("clients/update/member", $params), $this);
    }
    
    /**
     * Update group client projects.
     * 
     * @param  int  $groupId
     * @param  array  $data ['clientId' => '', 'projects' => [1,2,3]]
     * @return \Zaimea\SDK\Groups\Resources\Response
     */
    public function updateGroupClientProjects(int $groupId, array $data)
    {
        $params = array_merge(
            ['group' => $groupId],
            $data,
        );

        return new Response($this->put("clients/update/projects", $params), $this);
    }
    
    /**
     * Update group client role. #TODO, de fixat
     * 
     * @param  int  $groupId
     * @param  array  $data ['memberId' => '', 'role' => 'client']
     * @return \Zaimea\SDK\Groups\Resources\Response
     */
    public function updateGroupClientRole(int $groupId, array $data)
    {
        $params = array_merge(
            ['group' => $groupId],
            $data,
        );

        return new Response($this->put("clients/update/role", $params), $this);
    }
    
    /**
     * Remove the currently authenticated user as client member from the group.
     *
     * @param  int  $groupId
     * @param  int  $memberId
     * @return \Zaimea\SDK\Groups\Resources\Response
     */
    public function leaveGroupClientMember(int $groupId, int $memberId)
    {
        return new Response($this->put("clients/leave/member", [
            'group' => $groupId, 
            'member' => $memberId,
        ]), $this);
    }
    
    /**
     * Remove a group client member from the group.
     *
     * @param  int  $groupId
     * @param  int  $memberId
     * @return \Zaimea\SDK\Groups\Resources\Response
     */
    public function removeGroupClientMember(int $groupId, int $memberId)
    {
        return new Response($this->put("clients/remove/member", [
            'group' => $groupId, 
            'member' => $memberId,
        ]), $this);
    }

    /**
     * Get a group client member instance.
     *
     * @param  int  $groupId
     * @param  int  $userId
     * @return \Zaimea\SDK\Groups\Resources\ClientMember
     */
    public function clientMember(int $groupId, int $userId): ClientMember
    {
        return new ClientMember(
            $this->get("clients/read/member", ['group' => $groupId, 'user' =>  $userId])['data'], $this
        );
    }

    /**
     * Delete the given group client member invitation.
     *
     * @param  int  $groupId
     * @param  int  $invitationId
     * @return \Zaimea\SDK\Groups\Resources\Response
     */
    public function deleteGroupClientInvitation(int $groupId, int $invitationId)
    {
        return new Response($this->delete("clients/delete/invitation", [
            'group' => $groupId, 
            'invitationId' => $invitationId,
        ]), $this);
    }

    /**
     * Remove the given group client.
     *
     * @param  int  $groupId
     * @param  int  $clientId
     * @return \Zaimea\SDK\Groups\Resources\Response
     */
    public function deleteGroupClient(int $groupId, int $clientId)
    {
        return new Response($this->delete("clients/destroy", [
            'group' => $groupId, 
            'clientId' => $clientId,
        ]), $this);
    }

    /**
     * Get paginated group client projects.
     * 
     * @param  int  $groupId
     * @param  int  $clientId
     * @return \Zaimea\SDK\Groups\Resources\Project[]
     */
    public function clientProjects(int $groupId, int $clientId)
    {
        $params = array_merge(
            ['group' => $groupId, 'client' => $clientId],
        );

        return $this->transformCollection(
            $this->get("clients/get/projects", $params)['projects'],
            Project::class,
            ['groupId' => $groupId]
        );
    }

    /**
     * Get paginated group client roles.
     * 
     * @param  int  $groupId
     * @return \Zaimea\SDK\Groups\Resources\Role[]
     */
    public function clientRoles(int $groupId)
    {
        $params = array_merge(
            ['group' => $groupId],
        );

        return $this->transformCollection(
            $this->get("clients/get/roles", $params)['data'],
            Role::class,
            ['groupId' => $groupId]
        );
    }
}