<?php

declare(strict_types=1);

namespace Zaimea\SDK\Groups\Facades;

use Illuminate\Support\Facades\Facade;

use Zaimea\SDK\Groups\SDKManager;

/**
 * Client Start
 * @method static \Zaimea\SDK\Groups\Resources\Record[] clientRecords(array $filters = [], int $page = 1)
 * @method static \Zaimea\SDK\Groups\Resources\Group[] clientGroups()
 * Client End
 * 
 * Group Client Start
 * @method static \Zaimea\SDK\Groups\Resources\Client client(int $groupId, int $clientId)
 * @method static \Zaimea\SDK\Groups\Resources\Client[] clients(int $groupId, array $filters = [], int $page = 1)
 * @method static \Zaimea\SDK\Groups\Resources\ClientMember[] clientMembers(int $groupId, array $filters = [], int $page = 1)
 * @method static \Zaimea\SDK\Groups\Resources\Response createClientMember(int $groupId, array $data)
 * @method static \Zaimea\SDK\Groups\Resources\Response createClient(int $groupId, array $data)
 * @method static \Zaimea\SDK\Groups\Resources\Response updateClient(int $groupId, int $clientId, array $data)
 * @method static \Zaimea\SDK\Groups\Resources\Response updateClientMember(int $groupId, array $data)
 * @method static \Zaimea\SDK\Groups\Resources\Response updateClientProjects(int $groupId, array $data)
 * @method static \Zaimea\SDK\Groups\Resources\Response updateClientRole(int $groupId, array $data)
 * @method static \Zaimea\SDK\Groups\Resources\Response leaveClientMember(int $groupId, int $memberId)
 * @method static \Zaimea\SDK\Groups\Resources\Response removeClientMember(int $groupId, int $memberId)
 * @method static \Zaimea\SDK\Groups\Resources\ClientMember clientMember(int $groupId, int $userId)
 * @method static \Zaimea\SDK\Groups\Resources\Response deleteClient(int $groupId, int $clientId)
 * @method static \Zaimea\SDK\Groups\Resources\Project[] clientProjects(int $groupId, int $clientId)
 * @method static \Zaimea\SDK\Groups\Resources\Role[] clientRoles(int $groupId)
 * Group Client End
 * 
 * Group Start
 * @method static array groups()
 * @method static void deleteGroup(int $groupId)
 * @method static \Zaimea\SDK\Groups\Resources\Group group(int $groupId)
 * @method static \Zaimea\SDK\Groups\Resources\Group mountGroup(int $groupId)
 * @method static \Zaimea\SDK\Groups\Resources\Group setCurrentGroup(int $groupId)
 * @method static \Zaimea\SDK\Groups\Resources\Group createGroup(array $data, bool $wait = true)
 * @method static \Zaimea\SDK\Groups\Resources\Group updateGroup(int $groupId, array $data)
 * @method static \Zaimea\SDK\Groups\Resources\Group updateGroupDetails(int $groupId, array $data)
 * @method static \Zaimea\SDK\Groups\Resources\Group updateGroupSettings(int $groupId, array $data)
 * @method static \Zaimea\SDK\Groups\Resources\Group transferGroup(int $groupId, array $data)
 * @method static \Zaimea\SDK\Groups\Resources\Group deleteGroup(int $groupId)
 * Group End
 * 
 * Record Start
 * @method static \Zaimea\SDK\Groups\Resources\Record record(int $groupId, int $recordId)
 * @method static \Zaimea\SDK\Groups\Resources\Record records(int $groupId, array $filters = [], int $page)
 * @method static \Zaimea\SDK\Groups\Resources\Record recordsAggregate(int $groupId, array $filters = [])
 * @method static \Zaimea\SDK\Groups\Resources\Resource updateGroupRecord(int $groupId, int $recordId, string $actionType)
 * @method static \Zaimea\SDK\Groups\Resources\Resource approveGroupRecord(int $groupId, int $recordId)
 * @method static \Zaimea\SDK\Groups\Resources\Resource disapproveGroupRecord(int $groupId, int $recordId)
 * @method static \Zaimea\SDK\Groups\Resources\Resource deleteGroupRecord(int $groupId, int $recordId)
 * Record End
 * 
 * Member Start
 * @method static \Zaimea\SDK\Groups\Resources\Member member($int $groupId, int $memberId)
 * @method static \Zaimea\SDK\Groups\Resources\Member members(int $groupId, array $filters = [], int $page)
 * @method static \Zaimea\SDK\Groups\Resources\Member createGroupMember(int $groupId, array $data)
 * Member End
 * 
 * @method static \Zaimea\SDK\Groups\Resources\User user()
 * @method static mixed get(string $uri)
 * @method static mixed post(string $uri, array $payload = [])
 * @method static mixed put(string $uri, array $payload = [])
 * @method static mixed delete(string $uri, array $payload = [])
 */
class Groups extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return SDKManager::class;
    }
}