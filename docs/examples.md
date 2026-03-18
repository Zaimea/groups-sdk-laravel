---
title: Examples
description: Examples for Zaimea Groups SDK Laravel
github: https://github.com/zaimea/groups-sdk-laravel/edit/main/docs/
onThisArticle: true
sidebar: true
rightbar: true
---

# Examples

## Basic Setup in Controller

```php
<?php

namespace App\Http\Controllers;

use Zaimea\SDK\Groups\SDK;

class GroupController extends Controller
{
    protected SDK $groups;

    public function __construct()
    {
        $this-&gt;groups = new SDK(config('groups-sdk.api_key'));
    }

    public function index()
    {
        $groups = $this-&gt;groups-&gt;groups();

        return view('groups.index', compact('groups'));
    }
}
```

Livewire Component Example
```php
<?php

namespace App\Livewire\Groups;

use Livewire\Component;
use Zaimea\SDK\Groups\SDK;

class GroupManager extends Component
{
    protected SDK $groups;
    public int $groupId;
    public array $members = [];

    public function mount(): void
    {
        $this->groups = new SDK($this->getToken());
        $this->loadMembers();
    }

    public function loadMembers(): void
    {
        $response = $this->groups->membersAll($this->groupId, [], 1);
        $this->members = $response['data'];
    }

    public function loadNextPage(): void
    {
        $response = $this->groups->membersAll($this->groupId, [], 2);
        $this->members = array_merge($this->members, $response['data']);
    }

    public function approveRecord(int $recordId): void
    {
        $result = $this->groups->approveGroupRecord($this->groupId, $recordId);

        if ($result['received']) {
            $this->dispatch('notify', message: 'Record approved!');
        }
    }

    private function getToken(): string
    {
        return auth()->user()->groups_api_token;
    }

    public function render()
    {
        return view('livewire.groups.manager');
    }
}
```

Dashboard with Aggregates
```php
<?php

namespace App\Http\Controllers;

use Zaimea\SDK\Groups\SDK;

class DashboardController extends Controller
{
    public function index()
    {
        $sdk = new SDK(config('groups-sdk.api_key'));
        
        $groupId = auth()->user()->current_group_id;

        // Get this month's aggregates
        $aggregates = $sdk->recordsAggregate($groupId, [
            'interval' => 'thisMonth',
            'decimal' => true,
        ]);

        // Calculate totals
        $totalWork = collect($aggregates['works'])->sum('aggregate');
        $totalHolidays = collect($aggregates['holidays'])->sum('aggregate');

        return view('dashboard', compact('aggregates', 'totalWork', 'totalHolidays'));
    }
}
```

API Resource Wrapper
```php
<?php

namespace App\Services;

use Zaimea\SDK\Groups\SDK;

class GroupsService
{
    protected SDK $sdk;

    public function __construct()
    {
        $this->sdk = new SDK(config('groups-sdk.api_key'));
    }

    /**
     * Get all members with automatic pagination handling
     */
    public function getAllMembers(int $groupId): array
    {
        return $this->sdk->membersAll($groupId, [], 50); // Max 50 pages safety
    }

    /**
     * Get weekly report data
     */
    public function getWeeklyReport(int $groupId): array
    {
        return $this->sdk->recordsAggregate($groupId, [
            'interval' => 'thisWeek',
            'for' => 'projects',
            'decimal' => true,
        ]);
    }

    /**
     * Bulk approve records
     */
    public function bulkApprove(int $groupId, array $recordIds): array
    {
        $results = [];
        
        foreach ($recordIds as $recordId) {
            $results[$recordId] = $this->sdk->approveGroupRecord($groupId, $recordId);
        }

        return $results;
    }
}
```

Error Handling
```php
use GuzzleHttp\Exception\ClientException;

try {
    $members = $sdk->members(999999); // Non-existent group
} catch (ClientException $e) {
    if ($e->getResponse()->getStatusCode() === 404) {
        // Group not found
        return redirect()->back()->with('error', 'Group not found');
    }
    
    if ($e->getResponse()->getStatusCode() === 403) {
        // No permission
        return redirect()->back()->with('error', 'Access denied');
    }
}
```