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
 * Colors Start
 * @method static \Zaimea\SDK\Groups\Resources\Color color(int $groupId, int $colorId)
 * @method static \Zaimea\SDK\Groups\Resources\Color[] colors(int $groupId)
 * @method static \Zaimea\SDK\Groups\Resources\Color createColor(int $groupId, array $data)
 * @method static \Zaimea\SDK\Groups\Resources\Response updateColor(int $groupId, array $data)
 * @method static \Zaimea\SDK\Groups\Resources\Response deleteColor(int $groupId, int $colorId)
 * Colors End
 * 
 * Counts Start
 * @method static \Zaimea\SDK\Groups\Resources\Count countEmployees()
 * @method static \Zaimea\SDK\Groups\Resources\Count countGroups()
 * @method static \Zaimea\SDK\Groups\Resources\Count countHours()
 * Counts End
 * 
 * Holidays Start
 * @method static \Zaimea\SDK\Groups\Resources\Response createHoliday(int $groupId, array $data)
 * @method static \Zaimea\SDK\Groups\Resources\Holiday holiday(int $groupId, int $holidayId)
 * @method static \Zaimea\SDK\Groups\Resources\Holiday[] holidays($groupId, array $filters = [], int $page = 1)
 * @method static \Zaimea\SDK\Groups\Resources\Response deleteGroupHoliday(int $groupId, int $recordId)
 * @method static \Zaimea\SDK\Groups\Resources\Record memberHoliday(int $groupId, int $holidayId)
 * @method static \Zaimea\SDK\Groups\Resources\Record[] memberHolidays($groupId, array $filters = [], int $page = 1)
 * @method static \Zaimea\SDK\Groups\Resources\Response deleteHolidayMember(int $groupId, int $recordId)
 * Holidays End
 * 
 * Lockings Start
 * @method static \Zaimea\SDK\Groups\Resources\Response createLocking(int $groupId, array $data)
 * @method static \Zaimea\SDK\Groups\Resources\Response updateLocking(int $groupId, array $data)
 * @method static \Zaimea\SDK\Groups\Resources\Locking locking(int $groupId, int $lockingId)
 * @method static \Zaimea\SDK\Groups\Resources\Locking[] lockings($groupId, int $page = 1)
 * @method static \Zaimea\SDK\Groups\Resources\Response deleteLocking(int $groupId, int $lockingId)
 * Lockings End
 * 
 * Medicals Start
 * @method static \Zaimea\SDK\Groups\Resources\Response createMedical(int $groupId, array $data)
 * @method static \Zaimea\SDK\Groups\Resources\Record medical(int $groupId, int $medicalId)
 * @method static \Zaimea\SDK\Groups\Resources\Record[] medicals($groupId, array $filters = [], int $page = 1)
 * @method static \Zaimea\SDK\Groups\Resources\Response updateMedical(int $groupId, int $medicalId, string $actionType)
 * @method static mixed approveMedical(int $groupId, int $medicalId)
 * @method static mixed disapproveMedical(int $groupId, int $medicalId)
 * @method static \Zaimea\SDK\Groups\Resources\Response deleteMedical(int $groupId, int $medicalId)
 * Medicals End
 * 
 * Member Start
 * @method static \Zaimea\SDK\Groups\Resources\Member member($int $groupId, int $memberId)
 * @method static \Zaimea\SDK\Groups\Resources\Member[] members(int $groupId, array $filters = [], int $page)
 * @method static \Zaimea\SDK\Groups\Resources\Response createMember(int $groupId, array $data)
 * @method static \Zaimea\SDK\Groups\Resources\Response updateMember(int $groupId, array $data)
 * @method static \Zaimea\SDK\Groups\Resources\Response updateMemberRole(int $groupId, array $data)
 * @method static \Zaimea\SDK\Groups\Resources\Response deleteMember(int $groupId, int $memberId)
 * @method static \Zaimea\SDK\Groups\Resources\Role[] memberRoles($groupId, array $filters = [], int $page = 1)
 * Member End
 * 
 * Monthly Quotas Start
 * @method static \Zaimea\SDK\Groups\Resources\MonthlyQuotas monthlyQuotas(int $groupId, array $data)
 * @method static \Zaimea\SDK\Groups\Resources\Response updateOrCreateMonthlyQuotas(int $groupId, array $data)
 * Monthly Quotas End
 * 
 * Projects Start
 * @method static \Zaimea\SDK\Groups\Resources\Project project(int $groupId, int $projectId)
 * @method static \Zaimea\SDK\Groups\Resources\Project[] projects($groupId, array $filters = [], int $page = 1)
 * @method static \Zaimea\SDK\Groups\Resources\Client[] projectClients($groupId, int $projectId)
 * @method static \Zaimea\SDK\Groups\Resources\Response createProject(int $groupId, array $data)
 * @method static \Zaimea\SDK\Groups\Resources\Task[] projectTasks($groupId, int $projectId)
 * @method static \Zaimea\SDK\Groups\Resources\User[] projectUsers($groupId, int $projectId)
 * @method static \Zaimea\SDK\Groups\Resources\Template[] projectTemplates($groupId, int $projectId)
 * @method static \Zaimea\SDK\Groups\Resources\Response updateProject(int $groupId, int $projectId, array $data)
 * @method static \Zaimea\SDK\Groups\Resources\Response updateProjectClients(int $groupId, int $projectId, array $data)
 * @method static \Zaimea\SDK\Groups\Resources\Response updateProjectTasks(int $groupId, int $projectId, array $data)
 * @method static \Zaimea\SDK\Groups\Resources\Response updateProjectTemplates(int $groupId, int $projectId, array $data)
 * @method static \Zaimea\SDK\Groups\Resources\Response updateProjectUsers(int $groupId, int $projectId, array $data)
 * @method static \Zaimea\SDK\Groups\Resources\Response deleteProject(int $groupId, int $projectId)
 * Projects End
 * 
 * Record Start
 * @method static \Zaimea\SDK\Groups\Resources\Record record(int $groupId, int $recordId)
 * @method static \Zaimea\SDK\Groups\Resources\Record[] records(int $groupId, array $filters = [], int $page)
 * @method static \Zaimea\SDK\Groups\Resources\Record recordsAggregate(int $groupId, array $filters = [])
 * @method static \Zaimea\SDK\Groups\Resources\Resource updateRecord(int $groupId, int $recordId, string $actionType)
 * @method static \Zaimea\SDK\Groups\Resources\Resource approveRecord(int $groupId, int $recordId)
 * @method static \Zaimea\SDK\Groups\Resources\Resource disapproveRecord(int $groupId, int $recordId)
 * @method static \Zaimea\SDK\Groups\Resources\Resource deleteRecord(int $groupId, int $recordId)
 * Record End
 * 
 * Reports Start
 * @method static \Zaimea\SDK\Groups\Resources\Resource reportFields(int $groupId)
 * @method static \Zaimea\SDK\Groups\Resources\Response reportGenerate(int $groupId, array $data)
 * Reports End
 * 
 * Roles Start
 * @method static \Zaimea\SDK\Groups\Resources\Role role(int $groupId, int $roleId)
 * @method static \Zaimea\SDK\Groups\Resources\Role[] roles($groupId, array $filters = [], int $page = 1)
 * @method static array rolePermissions(int $groupId, int $roleId)
 * @method static \Zaimea\SDK\Groups\Resources\Response createRole(int $groupId, array $data)
 * @method static \Zaimea\SDK\Groups\Resources\Resource updateRole(int $groupId, array $data)
 * @method static \Zaimea\SDK\Groups\Resources\Permission[] groupPermissions($groupId, array $filters = [], int $page = 1)
 * @method static \Zaimea\SDK\Groups\Resources\Resource updateRolePermissions(int $groupId, array $data)
 * @method static \Zaimea\SDK\Groups\Resources\Response deleteRole(int $groupId, int $roleId)
 * Roles End
 * 
 * Group Start
 * @method static array groups()
 * @method static void deleteGroup(int $groupId)
 * @method static \Zaimea\SDK\Groups\Resources\Group group(int $groupId)
 * @method static \Zaimea\SDK\Groups\Resources\Group[] groups()
 * @method static \Zaimea\SDK\Groups\Resources\GroupMount mountGroup(int $groupId)
 * @method static \Zaimea\SDK\Groups\Resources\Response setCurrentGroup(int $groupId)
 * @method static \Zaimea\SDK\Groups\Resources\Group createGroup(array $data, bool $wait = true)
 * @method static \Zaimea\SDK\Groups\Resources\Response updateGroup(int $groupId, array $data)
 * @method static \Zaimea\SDK\Groups\Resources\Response updateGroupDetails(int $groupId, array $data)
 * @method static \Zaimea\SDK\Groups\Resources\Response updateGroupSettings(int $groupId, array $data)
 * @method static \Zaimea\SDK\Groups\Resources\Response transferGroup(int $groupId, array $data)
 * @method static \Zaimea\SDK\Groups\Resources\Response deleteGroup(int $groupId)
 * Group End
 * 
 * Tasks Start
 * @method static \Zaimea\SDK\Groups\Resources\Task task(int $groupId, int $taskId)
 * @method static \Zaimea\SDK\Groups\Resources\Tasks[] tasks($groupId, array $filters = [], int $page = 1)
 * @method static \Zaimea\SDK\Groups\Resources\Response createProjectTask(int $groupId, array $data)
 * @method static \Zaimea\SDK\Groups\Resources\Project[] taskProjects($groupId, int $taskId)
 * @method static \Zaimea\SDK\Groups\Resources\Response updateTask(int $groupId, int $taskId, array $data)
 * @method static \Zaimea\SDK\Groups\Resources\Response updateTaskProjects(int $groupId, int $taskId, array $data)
 * @method static \Zaimea\SDK\Groups\Resources\Response deleteTask(int $groupId, int $taskId)
 * Tasks End
 * 
 * Templates Start
 * @method static \Zaimea\SDK\Groups\Resources\Template template(int $groupId, int $templateId)
 * @method static \Zaimea\SDK\Groups\Resources\Template[] templates($groupId, array $filters = [], int $page = 1)
 * @method static \Zaimea\SDK\Groups\Resources\Response createProjectTemplate(int $groupId, array $data)
 * @method static \Zaimea\SDK\Groups\Resources\Project[] templateProjects($groupId, int $templateId)
 * @method static \Zaimea\SDK\Groups\Resources\Response updateTemplate(int $groupId, int $templateId, array $data)
 * @method static \Zaimea\SDK\Groups\Resources\Response updateTemplateProjects(int $groupId, int $templateId, array $data)
 * @method static \Zaimea\SDK\Groups\Resources\Response deleteTemplate(int $groupId, int $templateId)
 * Templates End
 * 
 * Vacations Start
 * @method static \Zaimea\SDK\Groups\Resources\Response createVacation(int $groupId, array $data)
 * @method static \Zaimea\SDK\Groups\Resources\Record vacation(int $groupId, int $vacationId)
 * @method static \Zaimea\SDK\Groups\Resources\Record[] vacations($groupId, array $filters = [], int $page = 1)
 * @method static \Zaimea\SDK\Groups\Resources\Response updateVacation(int $groupId, int $vacationId, string $actionType)
 * @method static \Zaimea\SDK\Groups\Resources\Response approveVacation(int $groupId, int $vacationId)
 * @method static \Zaimea\SDK\Groups\Resources\Response disapproveVacation(int $groupId, int $vacationId)
 * @method static \Zaimea\SDK\Groups\Resources\Response deleteVacation(int $groupId, int $vacationId)
 * Vacations End
 * 
 * Invitations Start
 * @method static \Zaimea\SDK\Groups\Resources\Invitation acceptMemberInvitation(int $invitationId) 
 * @method static \Zaimea\SDK\Groups\Resources\Response deleteMemberInvitation(int $invitationId)
 * @method static \Zaimea\SDK\Groups\Resources\invitation acceptClientInvitation(int $invitationId)
 * @method static \Zaimea\SDK\Groups\Resources\Response deleteClientInvitation(int $invitationId)
 * Invitations End
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