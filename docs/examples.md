---
title: Examples
description: Real-world examples and use cases for the Zaimea Groups SDK
github: https://github.com/zaimea/groups-sdk-laravel/edit/main/docs/
onThisArticle: true
sidebar: true
rightbar: true
---

# Examples

[[TOC]]

## Introduction

This page contains practical, real-world examples of using the Zaimea Groups SDK. These examples demonstrate common patterns and workflows you can adapt for your application.

## Basic Setup

### Initializing in a Controller

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Zaimea\SDK\Groups\Facades\Groups;

class GroupController extends Controller
{
    public function index()
    {
        $groups = Groups::groups();

        return view('groups.index', compact('groups'));
    }
}
```

### Using in a Service Class

```php
<?php

namespace App\Services;

use Zaimea\SDK\Groups\Facades\Groups;
use Zaimea\SDK\Groups\Exceptions\ValidationException;

class GroupService
{
    public function createWithDefaults(array $data): array
    {
        try {
            $group = Groups::createGroup([
                'name' => $data['name']
            ]);

            // Set default settings
            Groups::updateGroupSettings($group->groupId, [
                'lang' => 'en',
                'time_format' => '24h',
                'week_start' => 'monday'
            ]);

            return ['success' => true, 'group' => $group];

        } catch (ValidationException $e) {
            return ['success' => false, 'errors' => $e->errors()];
        }
    }
}
```

## Group Management Examples

### Creating a Complete Group Setup

```php
public function setupNewGroup($groupName)
{
    // 1. Create the group
    $group = Groups::createGroup(['name' => $groupName]);
    $groupId = $group->groupId;

    // 2. Configure settings
    Groups::updateGroupSettings($groupId, [
        'lang' => 'en',
        'time_format' => '24h',
        'date_format' => 'Y-m-d',
        'week_start' => 'monday',
        'tracking_mode' => 'default',
        'projectRequired' => true
    ]);

    // 3. Add contact details
    Groups::updateGroupDetails($groupId, [
        'phone' => '+49 123 456789',
        'adress' => 'Business Street 123',
        'zip' => '10115',
        'city' => 'Berlin',
        'country' => 'Germany'
    ]);

    // 4. Create default colors
    $this->setupDefaultColors($groupId);

    return $group;
}

private function setupDefaultColors($groupId)
{
    $colors = [
        ['name' => 'Primary', 'color_licht' => 'blue', 'color_licht_value' => 200, 
         'color_dark' => 'blue', 'color_dark_value' => 700],
        ['name' => 'Success', 'color_licht' => 'green', 'color_licht_value' => 200, 
         'color_dark' => 'green', 'color_dark_value' => 700],
        ['name' => 'Warning', 'color_licht' => 'orange', 'color_licht_value' => 200, 
         'color_dark' => 'orange', 'color_dark_value' => 700],
    ];

    foreach ($colors as $color) {
        Groups::createColor($groupId, $color);
    }
}
```

### Batch Group Operations

```php
public function syncGroups()
{
    $localGroups = Group::all();
    $remoteGroups = Groups::groups();

    // Create mapping of remote groups
    $remoteMap = collect($remoteGroups)->keyBy('groupId');

    foreach ($localGroups as $local) {
        if (isset($remoteMap[$local->zaimea_id])) {
            // Update existing
            Groups::updateGroup($local->zaimea_id, [
                'name' => $local->name,
                'description' => $local->description
            ]);
        } else {
            // Create new
            $new = Groups::createGroup(['name' => $local->name]);
            $local->update(['zaimea_id' => $new->groupId]);
        }
    }
}
```

## Member Management Examples

### Inviting Multiple Members

```php
public function inviteMembers($groupId, array $emails)
{
    $results = ['success' => [], 'failed' => []];

    foreach ($emails as $email) {
        try {
            $response = Groups::createMember($groupId, [
                'email' => $email,
                'role' => 'member',
                'rate' => '08:00:00',
                'quota_percent' => 100,
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

            $results['success'][] = $email;

        } catch (\Exception $e) {
            $results['failed'][] = [
                'email' => $email,
                'error' => $e->getMessage()
            ];
        }
    }

    return $results;
}
```

### Updating Member Work Schedule

```php
public function updateWorkSchedule($groupId, $memberId, array $schedule)
{
    // $schedule example: ['monday' => 8, 'tuesday' => 8, 'wednesday' => 4, ...]

    $workingDays = [];
    $totalHours = 0;

    foreach ($schedule as $day => $hours) {
        $workingDays[$day] = $hours > 0;
        $totalHours += $hours;
    }

    // Calculate quota percentage (assuming 40h week = 100%)
    $quotaPercent = ($totalHours / 40) * 100;

    // Format rate as HH:MM:SS
    $dailyAverage = $totalHours / count(array_filter($workingDays));
    $rate = sprintf('%02d:00:00', $dailyAverage);

    return Groups::updateMember($groupId, [
        'memberId' => $memberId,
        'rate' => $rate,
        'quota_percent' => round($quotaPercent),
        'working_days' => $workingDays,
    ]);
}
```

## Project Management Examples

### Creating a Project with Full Configuration

```php
public function createCompleteProject($groupId, $data)
{
    // Create project
    $response = Groups::createProject($groupId, [
        'form' => [
            'title' => $data['title'],
            'description' => $data['description'] ?? '',
            'users' => $data['user_ids'] ?? [],
            'clients' => $data['client_ids'] ?? [],
            'tasks' => [],
            'templates' => [],
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
                'night_shift' => ['bonus' => '20', 'start' => '22:00', 'end' => '06:00'],
            ],
            'shift_models' => '{}',
            'status' => 1
        ]
    ]);

    return $response;
}
```

### Cloning a Project

```php
public function cloneProject($groupId, $sourceProjectId, $newTitle)
{
    // Get source project
    $source = Groups::project($groupId, $sourceProjectId);

    // Get related data
    $tasks = Groups::projectTasks($groupId, $sourceProjectId);
    $templates = Groups::projectTemplates($groupId, $sourceProjectId);
    $users = Groups::projectUsers($groupId, $sourceProjectId);
    $clients = Groups::projectClients($groupId, $sourceProjectId);

    // Create new project
    $newProject = Groups::createProject($groupId, [
        'form' => [
            'title' => $newTitle,
            'description' => $source->description ?? '',
            'users' => collect($users)->pluck('id')->toArray(),
            'clients' => collect($clients)->pluck('id')->toArray(),
            'tasks' => [], // Will add after
            'templates' => collect($templates)->pluck('id')->toArray(),
            'status' => 1
        ]
    ]);

    // Clone tasks
    foreach ($tasks as $task) {
        Groups::createProjectTask($groupId, [
            'title' => $task->title,
            'description' => $task->description ?? '',
            'projects' => [$newProject->projectId],
            'status' => $task->status ?? 1
        ]);
    }

    return $newProject;
}
```

## Time Tracking Examples

### Submitting a Time Record

```php
public function submitTimeRecord($groupId, $data)
{
    // Note: Creating records directly via SDK
    // Usually done through the API endpoint

    $record = [
        'user_id' => auth()->id(),
        'project_id' => $data['project_id'],
        'task_id' => $data['task_id'] ?? null,
        'start' => $data['start'],      // Y-m-d H:i:s
        'end' => $data['end'],          // Y-m-d H:i:s
        'pause' => $data['pause'] ?? 0, // minutes
        'note' => $data['note'] ?? '',
    ];

    // Submit to records endpoint
    return Groups::post('records/store', $record);
}
```

### Generating Monthly Report

```php
public function generateMonthlyReport($groupId, $month, $year)
{
    $start = sprintf('%04d-%02d-01', $year, $month);
    $end = date('Y-m-t', strtotime($start));

    $report = Groups::reportGenerate($groupId, [
        'options' => [
            'period_start' => $start,
            'period_end' => $end,
            'users' => [], // All users
            'projects' => [], // All projects
            'approved' => '1',
        ],
        'checkOptions' => [
            'checkClient' => true,
            'checkProject' => true,
            'checkStart' => true,
            'checkFinish' => true,
            'checkPause' => true,
            'checkDuration' => true,
            'checkWorked' => true,
            'checkNote' => true,
            'groupBy' => 'user',
        ]
    ]);

    // Returns PDF content - save or stream
    return response($report)
        ->header('Content-Type', 'application/pdf')
        ->header('Content-Disposition', 'attachment; filename="report.pdf"');
}
```

### Getting Aggregated Statistics

```php
public function getDashboardStats($groupId)
{
    $now = now();

    // This month's aggregates
    $aggregates = Groups::recordsAggregate($groupId, [
        'start' => $now->copy()->startOfMonth()->format('Y-m-d'),
        'end' => $now->copy()->endOfMonth()->format('Y-m-d'),
        'interval' => 'thisMonth',
        'for' => 'projects',
        'decimal' => true
    ]);

    $stats = [
        'total_work_hours' => collect($aggregates['works'])->sum('duration'),
        'holiday_days' => count($aggregates['holidays']),
        'vacation_days' => count($aggregates['vacations']),
        'medical_days' => count($aggregates['medicals']),
    ];

    return $stats;
}
```

## Leave Management Examples

### Requesting Vacation

```php
public function requestVacation($groupId, $startDate, $endDate, $notes = '')
{
    // Calculate dates array
    $dates = [];
    $current = strtotime($startDate);
    $end = strtotime($endDate);

    while ($current <= $end) {
        $dates[] = date('Y-m-d', $current);
        $current = strtotime('+1 day', $current);
    }

    return Groups::createVacation($groupId, [
        'title' => 'Vacation Request',
        'notes' => $notes,
        'dates' => implode(', ', $dates)
    ]);
}
```

### Processing Pending Approvals

```php
public function processPendingApprovals($groupId)
{
    $results = [
        'vacations' => ['approved' => 0, 'rejected' => 0],
        'medicals' => ['approved' => 0, 'rejected' => 0],
        'records' => ['approved' => 0, 'rejected' => 0]
    ];

    // Get pending vacations (you'd need to filter by status)
    $vacations = Groups::vacations($groupId, ['search' => 'pending'], 1);
    foreach ($vacations as $vacation) {
        // Auto-approve if conditions met
        if ($this->canAutoApprove($vacation)) {
            Groups::approveVacation($groupId, $vacation->id);
            $results['vacations']['approved']++;
        }
    }

    // Similar for medicals and records...

    return $results;
}
```

## Client Management Examples

### Onboarding a New Client

```php
public function onboardClient($groupId, $clientData)
{
    // 1. Create client
    $client = Groups::createClient($groupId, [
        'client_name' => $clientData['company_name'],
        'client_adress' => $clientData['address'],
        'projectBinds' => $clientData['project_ids'] ?? [],
        'status' => 1
    ]);

    $clientId = $client->id ?? null;

    // 2. Add client members
    foreach ($clientData['contacts'] as $contact) {
        Groups::createClientMember($groupId, [
            'email' => $contact['email'],
            'role' => $contact['role'] ?? 'client',
            'clientId' => $clientId,
            'status' => 1
        ]);
    }

    // 3. Update project associations if needed
    if (!empty($clientData['project_ids'])) {
        Groups::updateClientProjects($groupId, [
            'clientId' => $clientId,
            'projects' => $clientData['project_ids']
        ]);
    }

    return $client;
}
```

## Advanced Patterns

### Caching Group Data

```php
use Illuminate\Support\Facades\Cache;

public function getCachedGroup($groupId)
{
    return Cache::remember("group.{$groupId}", 3600, function () use ($groupId) {
        return Groups::group($groupId);
    });
}

public function clearGroupCache($groupId)
{
    Cache::forget("group.{$groupId}");
    Cache::forget("group.{$groupId}.members");
    Cache::forget("group.{$groupId}.projects");
}
```

### Handling Rate Limits

```php
use Zaimea\SDK\Groups\Exceptions\RateLimitExceededException;

public function safeApiCall($callback, $maxRetries = 3)
{
    $attempts = 0;

    while ($attempts < $maxRetries) {
        try {
            return $callback();
        } catch (RateLimitExceededException $e) {
            $attempts++;

            if ($attempts >= $maxRetries) {
                throw $e;
            }

            // Wait until rate limit resets
            $waitTime = $e->rateLimitResetsAt - time();
            if ($waitTime > 0) {
                sleep($waitTime + 1);
            }
        }
    }
}

// Usage
$groups = $this->safeApiCall(function () {
    return Groups::groups();
});
```

### Batch Operations with Chunks

```php
public function syncMembersInChunks($groupId, $members)
{
    $chunks = array_chunk($members, 10); // Process 10 at a time
    $results = [];

    foreach ($chunks as $chunk) {
        foreach ($chunk as $member) {
            try {
                Groups::createMember($groupId, $member);
                $results[] = ['email' => $member['email'], 'status' => 'success'];
            } catch (\Exception $e) {
                $results[] = [
                    'email' => $member['email'], 
                    'status' => 'error',
                    'message' => $e->getMessage()
                ];
            }
        }

        // Small delay between chunks to avoid rate limiting
        usleep(100000); // 100ms
    }

    return $results;
}
```

### Event Listener for Group Changes

```php
<?php

namespace App\Listeners;

use Zaimea\SDK\Groups\Facades\Groups;
use App\Events\GroupSettingsChanged;

class SyncGroupSettings
{
    public function handle(GroupSettingsChanged $event)
    {
        $groupId = $event->groupId;
        $settings = $event->settings;

        try {
            Groups::updateGroupSettings($groupId, [
                'lang' => $settings['language'] ?? 'en',
                'time_format' => $settings['time_format'] ?? '24h',
                'week_start' => $settings['week_start'] ?? 'monday',
            ]);

            // Log success
            \Log::info("Group {$groupId} settings synced");

        } catch (\Exception $e) {
            \Log::error("Failed to sync group settings: " . $e->getMessage());
        }
    }
}
```

## Testing with the SDK

### Mocking in Tests

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use Zaimea\SDK\Groups\Facades\Groups;
use Zaimea\SDK\Groups\SDKManager;

class GroupControllerTest extends TestCase
{
    public function test_can_list_groups()
    {
        // Mock the facade
        Groups::shouldReceive('groups')
            ->once()
            ->andReturn([
                (object)['groupId' => 1, 'name' => 'Test Group']
            ]);

        $response = $this->getJson('/api/groups');

        $response->assertOk()
            ->assertJsonCount(1);
    }
}
```

### Using Test Credentials

```php
// In your phpunit.xml or .env.testing
ZAIMEA_CLIENT_ID=test_client_id
ZAIMEA_CLIENT_SECRET=test_client_secret
ZAIMEA_API_URL=https://resources.click/api/v1/groups/
```

## Common Pitfalls and Solutions

### Handling Timeouts

```php
public function createGroupWithExtendedTimeout()
{
    // Temporarily increase timeout for this operation
    $originalTimeout = Groups::getTimeout();
    Groups::setTimeout(120);

    try {
        $group = Groups::createGroup(['name' => 'Large Group']);
    } finally {
        // Always restore original timeout
        Groups::setTimeout($originalTimeout);
    }

    return $group;
}
```

### Dealing with Pagination

```php
public function getAllMembers($groupId)
{
    $allMembers = [];
    $page = 1;

    do {
        $members = Groups::members($groupId, [], $page);
        $allMembers = array_merge($allMembers, $members);
        $page++;
    } while (count($members) > 0); // Continue until empty

    return $allMembers;
}
```

### Proper Error Messages

```php
public function createGroupWithFeedback($data)
{
    try {
        $group = Groups::createGroup($data);
        return redirect()->back()->with('success', 'Group created successfully!');

    } catch (ValidationException $e) {
        $errors = $e->errors();
        $message = 'Validation failed: ';

        foreach ($errors as $field => $error) {
            $message .= "{$field}: " . implode(', ', $error) . '; ';
        }

        return redirect()->back()->with('error', $message);

    } catch (ForbiddenException $e) {
        return redirect()->back()->with('error', 'You do not have permission to create groups.');

    } catch (\Exception $e) {
        \Log::error('Group creation failed', ['error' => $e->getMessage()]);
        return redirect()->back()->with('error', 'An unexpected error occurred. Please try again.');
    }
}
```
