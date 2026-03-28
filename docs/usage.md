---
title: Usage Guide
description: Complete reference for using the Zaimea Groups SDK
github: https://github.com/zaimea/groups-sdk-laravel/edit/main/docs/
onThisArticle: true
sidebar: true
rightbar: true
---

# Usage Guide

[[TOC]]

## Introduction

This guide covers all available methods in the Zaimea Groups SDK. The SDK is organized around **Groups** - each group contains members, clients, projects, records, and various configuration settings.

## Quick Start

```php
use Zaimea\SDK\Groups\Facades\Groups;

// Get current authenticated user
$user = Groups::user();

// List all groups
$groups = Groups::groups();

// Work with a specific group
$group = Groups::group(123);
```

## Authentication & User Management

### Get Authenticated User

Retrieve the currently authenticated user's information:

```php
$user = Groups::user();

echo $user->id;              // User ID
echo $user->name;            // Full name
echo $user->email;           // Email address
echo $user->canCreateGroups; // Boolean permission
```

## Groups Management

### List All Groups

Get all groups accessible to the authenticated user:

```php
$groups = Groups::groups();

foreach ($groups as $group) {
    echo $group->groupId;
    echo $group->status;      // 1 = active, 0 = inactive
    echo $group->createdAt;   // ISO 8601 datetime
}
```

### Get Single Group

Retrieve detailed information about a specific group:

```php
$group = Groups::group(123);

// Available methods on group resource
$group->group();        // Refresh group data
$group->groups();       // Get all groups
$group->mountGroup();   // Get mount data (plugins, configs)
$group->setCurrentGroup(); // Set as current active group
$group->delete();       // Delete the group
```

### Create a New Group

```php
// Create and wait for provisioning (default)
$group = Groups::createGroup(['name' => 'My New Group']);

// Create without waiting (async)
$group = Groups::createGroup(['name' => 'My New Group'], false);
```

The `createGroup` method automatically polls until the group status becomes active (1).

### Update Group

```php
// Update basic info
Groups::updateGroup(123, [
    'name' => 'Updated Name',
    'description' => 'New description'
]);

// Update details (contact information)
Groups::updateGroupDetails(123, [
    'phone' => '+49 123 456789',
    'adress' => 'Street 123',
    'zip' => '12345',
    'city' => 'Berlin',
    'country' => 'Germany'
]);

// Update settings
Groups::updateGroupSettings(123, [
    'lang' => 'en',
    'time_format' => '24h',
    'date_format' => 'Y-m-d',
    'week_start' => 'monday',
    'tracking_mode' => 'default',
    'record_type' => 'duration',
    'template_sign' => true,
    'pluginsSelected' => ['plugin1', 'plugin2'],
    'configsSelected' => ['config1'],
    'projectRequired' => true
]);
```

### Transfer Group Ownership

```php
Groups::transferGroup(123, [
    'password' => 'current_owner_password',
    'email' => 'new_owner@example.com'
]);
```

### Delete Group

```php
// Via facade
Groups::deleteGroup(123);

// Via resource
$group = Groups::group(123);
$group->delete();
```

### Mount Group Data

Get comprehensive group configuration including plugins and settings:

```php
$mount = Groups::mountGroup(123);

echo $mount->groupId;
echo $mount->group;           // Group state array
echo $mount->plugins;          // Available plugins
echo $mount->configs;          // Available configs
echo $mount->pluginsSelected; // Active plugins
echo $mount->configsSelected; // Active configs
echo $mount->projectRequired;  // Boolean
```

### Set Current Group

```php
$response = Groups::setCurrentGroup(123);
// Returns Response resource with status and message
```

## Members Management

### List Members

```php
$members = Groups::members(123, [
    'search' => 'user id or name'
], 1); // page number

foreach ($members as $member) {
    echo $member->groupId;
    // Access member properties
}
```

### Get Single Member

```php
$member = Groups::member(123, 456); // groupId, memberId

// Available methods
$member->member(456);      // Get specific member
$member->members();        // List all members
```

### Create Member

```php
$response = Groups::createMember(123, [
    'email' => 'new.member@example.com',
    'role' => 'member',
    'rate' => '07:00:00',      // Daily working hours
    'quota_percent' => 100,     // Employment percentage
    'working_days' => [
        'monday' => true,
        'tuesday' => true,
        'wednesday' => true,
        'thursday' => true,
        'friday' => true,
        'saturday' => false,
        'sunday' => false,
    ],
    'status' => 1
]);
```

### Update Member

```php
// Update member details
Groups::updateMember(123, [
    'memberId' => 456,
    'rate' => '08:00:00',
    'quota_percent' => 80,
    'working_days' => [
        'monday' => true,
        'tuesday' => true,
        'wednesday' => true,
        'thursday' => true,
        'friday' => true,
        'saturday' => false,
        'sunday' => false,
    ],
    'status' => 1
]);

// Update member role
Groups::updateMemberRole(123, [
    'memberId' => 456,
    'role' => 'admin'
]);
```

### Delete Member

```php
Groups::deleteMember(123, 456);
```

### Member Roles

```php
$roles = Groups::memberRoles(123, [
    'search' => 'key / name / permissions / description'
], 1);
```

## Clients Management

### List Clients

```php
$clients = Groups::clients(123, [
    'search' => 'client name'
], 1);
```

### Get Single Client

```php
$client = Groups::client(123, 456);

// Available methods
$client->client(456);   // Get client
$client->clients();      // List clients
```

### Create Client

```php
$response = Groups::createClient(123, [
    'client_name' => 'Acme Corporation',
    'client_address' => '123 Business Street',
    'projectBinds' => [1, 2, 3], // Associated project IDs
    'status' => 1
]);
```

### Update Client

```php
Groups::updateClient(123, 456, [
    'client_name' => 'Updated Name',
    'client_address' => 'New Address',
    'projects' => '1,2,3',
    'status' => 1
]);
```

### Delete Client

```php
Groups::deleteClient(123, 456);
```

## Client Members Management

### List Client Members

```php
$members = Groups::clientMembers(123, [
    'search' => 'client id'
], 1);
```

### Get Single Client Member

```php
$member = Groups::clientMember(123, 789); // groupId, userId
```

### Create Client Member

```php
$response = Groups::createClientMember(123, [
    'email' => 'client.user@example.com',
    'role' => 'client',
    'clientId' => 456,
    'status' => 1
]);
```

### Update Client Member

```php
// Update member data
Groups::updateClientMember(123, [
    'memberId' => 789,
    'clientId' => 456,
    'status' => 1
]);

// Update role
Groups::updateClientRole(123, [
    'memberId' => 789,
    'role' => 'client_admin'
]);

// Update associated projects
Groups::updateClientProjects(123, [
    'clientId' => 456,
    'projects' => [1, 2, 3]
]);
```

### Remove Client Member

```php
// Member leaves voluntarily
Groups::leaveClientMember(123, 789);

// Admin removes member
Groups::removeClientMember(123, 789);
```

### Client Projects & Roles

```php
// Get projects accessible to client
$projects = Groups::clientProjects(123, 456);

// Get available client roles
$roles = Groups::clientRoles(123);
```

## Projects Management

### List Projects

```php
$projects = Groups::projects(123, [
    'search' => 'project title'
], 1);
```

### Get Single Project

```php
$project = Groups::project(123, 456);
```

### Create Project

```php
$response = Groups::createProject(123, [
    'form' => [
        'title' => 'New Project',
        'description' => 'Project description',
        'users' => [1, 2],           // User IDs
        'clients' => [],             // Client IDs
        'tasks' => [],               // Task IDs
        'templates' => [],           // Template IDs
        'work_pause' => [
            'work' => [
                'time_1' => '08:00',
                'time_2' => '12:00',
                'time_3' => '13:00',
                'time_4' => '17:00'
            ],
            'pause' => [
                'time_1' => '12:00',
                'time_2' => '13:00',
                'time_3' => null,
                'time_4' => null
            ]
        ],
        'extra_pay' => [
            'above_tariff' => ['bonus' => '10'],
            'saturday' => ['bonus' => '50', 'start' => '00:00', 'end' => '23:59'],
            'sunday' => ['bonus' => '100', 'start' => '00:00', 'end' => '23:59'],
            'morning_shift' => ['bonus' => '5', 'start' => '06:00', 'end' => '14:00'],
            'afternoon_shift' => ['bonus' => '5', 'start' => '14:00', 'end' => '22:00'],
            'night_shift' => ['bonus' => '20', 'start' => '22:00', 'end' => '06:00'],
            'holiday' => ['bonus' => '100', 'start' => '00:00', 'end' => '23:59'],
        ],
        'shift_models' => '{}',
        'status' => 1  // 1 = active, 0 = inactive
    ]
]);
```

### Update Project

```php
// Update basic info
Groups::updateProject(123, 456, [
    'form' => [
        'title' => 'Updated Title',
        'description' => 'Updated description',
        'work_pause' => [...],
        'extra_pay' => [...],
        'shift_models' => '{}',
        'status' => 1
    ]
]);

// Update associations
Groups::updateProjectClients(123, 456, [
    'form' => ['clients' => ['1', '2']]
]);

Groups::updateProjectTasks(123, 456, [
    'form' => ['tasks' => ['1', '2']]
]);

Groups::updateProjectTemplates(123, 456, [
    'form' => ['templates' => ['1', '2']]
]);

Groups::updateProjectUsers(123, 456, [
    'form' => ['users' => ['1', '2']]
]);
```

### Delete Project

```php
Groups::deleteProject(123, 456);
```

### Project Relations

```php
// Get project clients
$clients = Groups::projectClients(123, 456);

// Get project tasks
$tasks = Groups::projectTasks(123, 456);

// Get project users
$users = Groups::projectUsers(123, 456);

// Get project templates
$templates = Groups::projectTemplates(123, 456);
```

## Tasks Management

### List Tasks

```php
$tasks = Groups::tasks(123, [
    'search' => 'title / description / created at'
], 1);
```

### Get Single Task

```php
$task = Groups::task(123, 456);
```

### Create Task

```php
$response = Groups::createProjectTask(123, [
    'title' => 'New Task',
    'description' => 'Task description',
    'projects' => [1, 2],  // Associated project IDs
    'status' => 1          // 1 = active, 0 = inactive
]);
```

### Update Task

```php
// Update task info
Groups::updateTask(123, 456, [
    'title' => 'Updated Title',
    'description' => 'Updated description',
    'status' => 1
]);

// Update project associations
Groups::updateTaskProjects(123, 456, [
    'projects' => ['1', '2']
]);
```

### Delete Task

```php
Groups::deleteTask(123, 456);
```

### Task Relations

```php
$projects = Groups::taskProjects(123, 456);
```

## Templates Management

### List Templates

```php
$templates = Groups::templates(123, [
    'search' => 'title / description / created at'
], 1);
```

### Get Single Template

```php
$template = Groups::template(123, 456);
```

### Create Template

```php
$response = Groups::createProjectTemplate(123, [
    'title' => 'New Template',
    'description' => 'Template description',
    'content' => 'Template content...',
    'projects' => [1, 2],  // Associated project IDs
    'status' => 1          // 1 = active, 0 = inactive
]);
```

### Update Template

```php
// Update template info
Groups::updateTemplate(123, 456, [
    'title' => 'Updated Title',
    'description' => 'Updated description',
    'content' => 'Updated content...',
    'status' => 1
]);

// Update project associations
Groups::updateTemplateProjects(123, 456, [
    'projects' => ['1', '2']
]);
```

### Delete Template

```php
Groups::deleteTemplate(123, 456);
```

### Template Relations

```php
$projects = Groups::templateProjects(123, 456);
```

## Records (Time Tracking) Management

### List Records

```php
$records = Groups::records(123, [
    'search' => 'user id / scheduled or title',
    'users' => ['1', '2'],
    'start' => '2026-03-01',
    'end' => '2026-03-31'
], 1);
```

### Get Single Record

```php
$record = Groups::record(123, 456);
```

### Records Aggregate

Get aggregated data across multiple categories:

```php
$aggregates = Groups::recordsAggregate(123, [
    'users' => ['1', '2'],
    'start' => '2026-03-01',
    'end' => '2026-03-31',
    'interval' => 'thisMonth',  // thisDay, thisWeek, thisMonth, previousMonth, thisYear
    'for' => 'projects',        // projects, tasks, clients, none
    'decimal' => true
]);

// Returns array with:
$aggregates['works'];      // Work records
$aggregates['holidays'];   // Holiday records
$aggregates['vacations'];  // Vacation records
$aggregates['medicals'];   // Medical records
```

### Update Record Status

```php
// Generic update with action type
Groups::updateRecord(123, 456, 'approve');   // or 'disapprove'

// Convenience methods
Groups::approveRecord(123, 456);
Groups::disapproveRecord(123, 456);
```

### Delete Record

```php
Groups::deleteRecord(123, 456);
```

## Holidays Management

### Create Holiday

```php
$response = Groups::createHoliday(123, [
    'title' => 'Public Holiday',
    'description' => 'red',     // Color description
    'users' => ['1', '2'],      // Affected user IDs
    'dates' => '2026-03-20, 2026-03-21'
]);
```

### List Holidays

```php
$holidays = Groups::holidays(123, [
    'search' => 'user id'
], 1);
```

### Get Single Holiday

```php
$holiday = Groups::holiday(123, 456);
```

### Delete Holiday

```php
Groups::deleteGroupHoliday(123, 456);
```

### Member Holidays

```php
// Get member holiday record
$record = Groups::memberHoliday(123, 456);

// List member holidays
$records = Groups::memberHolidays(123, [
    'search' => 'user id'
], 1);

// Delete member holiday record
Groups::deleteHolidayMember(123, 456);
```

## Vacations Management

### Create Vacation

```php
$response = Groups::createVacation(123, [
    'title' => 'Summer Vacation',
    'notes' => 'Personal time',
    'dates' => '2026-07-01, 2026-07-15'
]);
```

### List Vacations

```php
$vacations = Groups::vacations(123, [
    'search' => 'user id, date, title'
], 1);
```

### Get Single Vacation

```php
$vacation = Groups::vacation(123, 456);
```

### Update Vacation Status

```php
// Generic update
Groups::updateVacation(123, 456, 'approve');  // or 'disapprove'

// Convenience methods
Groups::approveVacation(123, 456);
Groups::disapproveVacation(123, 456);
```

### Delete Vacation

```php
Groups::deleteVacation(123, 456);
```

## Medicals Management

### Create Medical

```php
$response = Groups::createMedical(123, [
    'title' => 'Doctor Appointment',
    'notes' => 'Annual checkup',
    'dates' => '2026-03-20, 2026-03-21'
]);
```

### List Medicals

```php
$medicals = Groups::medicals(123, [
    'search' => 'user id / date / title'
], 1);
```

### Get Single Medical

```php
$medical = Groups::medical(123, 456);
```

### Update Medical Status

```php
// Generic update
Groups::updateMedical(123, 456, 'approve');  // or 'disapprove'

// Convenience methods
Groups::approveMedical(123, 456);
Groups::disapproveMedical(123, 456);
```

### Delete Medical

```php
Groups::deleteMedical(123, 456);
```

## Lockings Management

Lockings are scheduled locks that prevent record modifications during specific time periods.

### Create Locking

```php
$response = Groups::createLocking(123, [
    'param' => '0 * * * *'  // Cron expression (every hour)
]);
```

### List Lockings

```php
$lockings = Groups::lockings(123, 1);
```

### Get Single Locking

```php
$locking = Groups::locking(123, 456);
```

### Update Locking

```php
Groups::updateLocking(123, [
    'lockingId' => 456,
    'param' => '0 0 * * *'  // Daily at midnight
]);
```

### Delete Locking

```php
Groups::deleteLocking(123, 456);
```

## Roles and Permissions Management

### List Roles

```php
$roles = Groups::roles(123, [
    'search' => 'key / name / description / permissions',
    'default' => true
], 1);
```

### Get Single Role

```php
$role = Groups::role(123, 456);
```

### Create Role

```php
$response = Groups::createRole(123, [
    'client' => false,
    'name' => 'Manager',
    'description' => 'Department manager role',
    'status' => true,
    'permissions' => ['view_records', 'approve_records']
]);
```

### Update Role

```php
Groups::updateRole(123, [
    'roleId' => 456,
    'client' => false,
    'name' => 'Senior Manager',
    'description' => 'Updated description',
    'status' => true
]);
```

### Delete Role

```php
Groups::deleteRole(123, 456);
```

### Role Permissions

```php
// Get permissions for a specific role
$permissions = Groups::rolePermissions(123, 456);

// Get all available permissions in group
$permissions = Groups::groupPermissions(123, [
    'search' => 'by title'
], 1);

// Update role permissions
Groups::updateRolePermissions(123, [
    'roleId' => 456,
    'permissions' => ['view_records', 'edit_records', 'delete_records']
]);
```

## Monthly Quotas Management

### Get Monthly Quotas

```php
$quotas = Groups::monthlyQuotas(123, [
    'year' => 2026
]);
```

### Update or Create Monthly Quotas

```php
Groups::updateOrCreateMonthlyQuotas(123, [
    'workday_minutes' => '07:00',
    'year' => 2026,
    'minutes' => [
        "1" => "06:00",   // January: 6 hours
        "2" => "07:00",   // February: 7 hours
        // ... months 1-12
    ]
]);
```

## Colors Management

Colors are used for visual organization of records and projects.

### List Colors

```php
$colors = Groups::colors(123);
```

### Get Single Color

```php
$color = Groups::color(123, 456);
```

### Create Color

```php
$color = Groups::createColor(123, [
    'name' => 'Primary Red',
    'color_licht' => 'red',
    'color_licht_value' => 200,
    'color_dark' => 'red',
    'color_dark_value' => 400
]);
```

### Update Color

```php
Groups::updateColor(123, [
    'colorId' => 456,
    'name' => 'Updated Red',
    'color_licht' => 'red',
    'color_licht_value' => 200,
    'color_dark' => 'red',
    'color_dark_value' => 400
]);
```

### Delete Color

```php
Groups::deleteColor(123, 456);
```

## Counts and Statistics

### Get Statistics

```php
// Count employees across all groups
$count = Groups::countEmployees();

// Count groups
$count = Groups::countGroups();

// Count hours (total tracked time)
$count = Groups::countHours();
```

## Reports

### Get Report Fields

```php
$fields = Groups::reportFields(123);
```

### Generate Report

```php
$response = Groups::reportGenerate(123, [
    'options' => [
        'period' => 'thisMonth',    // thisDay, thisWeek, thisMonth, previousMonth, thisYear
        'period_start' => '2026-03-01',
        'period_end' => '2026-03-31',
        'users' => ['1', '2'],
        'client' => '',
        'projects' => ['1', '2'],
        'approved' => '1',
    ],
    'checkOptions' => [
        'checkClient' => true,
        'checkProject' => false,
        'checkStart' => true,
        'checkFinish' => true,
        'checkPause' => true,
        'checkDuration' => true,
        'checkWorked' => true,
        'checkNote' => false,
        'checkType' => false,
        'checkApproved' => false,
        'groupBy' => 'date',        // date, user, project, client
    ]
]);
```

**Note:** This returns a PDF file directly from the API.

## Invitations

### Member Invitations

```php
// Accept invitation
$invitation = Groups::acceptMemberInvitation(789);

// Delete invitation
$response = Groups::deleteMemberInvitation(789);
```

### Client Invitations

```php
// Accept invitation
$invitation = Groups::acceptClientInvitation(789);

// Delete invitation
$response = Groups::deleteClientInvitation(789);
```

## Client Perspective (External Users)

When authenticated as a client member (not group member):

### Get Client Records

```php
$records = Groups::clientRecords([
    'search' => 'user id, scheduled or title',
    'users' => ['1', '2'],
    'groups' => ['10', '20'],
    'start' => '2026-03-01',
    'end' => '2026-03-31'
], 1);
```

### Get Client Groups

```php
$groups = Groups::clientGroups();
```

## Error Handling

The SDK throws specific exceptions for different error scenarios:

```php
use Zaimea\SDK\Groups\Exceptions\ValidationException;
use Zaimea\SDK\Groups\Exceptions\NotFoundException;
use Zaimea\SDK\Groups\Exceptions\ForbiddenException;
use Zaimea\SDK\Groups\Exceptions\RateLimitExceededException;
use Zaimea\SDK\Groups\Exceptions\FailedActionException;

try {
    $group = Groups::group(999999);
} catch (NotFoundException $e) {
    // Resource not found (404)
    echo "Group not found";
} catch (ValidationException $e) {
    // Validation failed (422)
    $errors = $e->errors();
    print_r($errors);
} catch (ForbiddenException $e) {
    // Permission denied (403)
    echo "Access denied";
} catch (RateLimitExceededException $e) {
    // Too many requests (429)
    $resetTime = $e->rateLimitResetsAt;
    echo "Rate limit resets at: " . date('Y-m-d H:i:s', $resetTime);
} catch (FailedActionException $e) {
    // Bad request (400)
    echo "Action failed: " . $e->getMessage();
}
```

## Advanced Usage

### Direct HTTP Requests

For endpoints not covered by dedicated methods:

```php
// GET request
$response = Groups::get('custom/endpoint', ['param' => 'value']);

// POST request
$response = Groups::post('custom/endpoint', ['key' => 'value']);

// PUT request
$response = Groups::put('custom/endpoint', ['key' => 'value']);

// DELETE request
$response = Groups::delete('custom/endpoint', ['id' => 123]);
```

### Working with the SDK Instance Directly

```php
use Zaimea\SDK\Groups\SDKManager;

// Resolve from container
$sdk = app(SDKManager::class);

// Or inject in constructor
class MyService
{
    public function __construct(
        protected SDKManager $groups
    ) {}

    public function doSomething()
    {
        return $this->groups->groups();
    }
}
```

### Custom Timeout

```php
// Set timeout for all subsequent requests
Groups::setTimeout(60);  // 60 seconds

// Get current timeout
$timeout = Groups::getTimeout();  // 60
```

### Retry Mechanism

The SDK includes a retry helper for operations that need polling:

```php
use Zaimea\SDK\Groups\SDK;

$sdk = new SDK($token);

$result = $sdk->retry(120, function () {
    // Try to get something
    $data = $this->getSomeData();
    return $data->isReady() ? $data : null;
}, 5);  // Retry every 5 seconds for up to 120 seconds
```
