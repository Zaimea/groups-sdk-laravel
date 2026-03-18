---
title: Usage
description: How to use Zaimea Groups SDK
github: https://github.com/zaimea/groups-sdk-laravel/edit/main/docs/
onThisArticle: true
sidebar: true
rightbar: true
---

# Zaimea Groups SDK Usage

[[TOC]]

## Initialize SDK

```php
use Zaimea\SDK\Groups\SDK;

// With API key directly
$sdk = new SDK('your-api-key');

// Or with config
$sdk = new SDK(config('groups.api_key'));

// With custom Guzzle instance
$sdk = new SDK('your-api-key', $customGuzzleClient);
```

## Authentication
Get current authenticated user:

```php
$user = $sdk->user();

echo $user->id;
echo $user->name;
echo $user->email;
```

## Groups Management
List all groups

```php
$groups = $sdk->groups();

foreach ($groups as $group) {
    echo $group->id . ': ' . $group->name;
}
```

Get single group

```php
$group = $sdk->group(1);

echo $group->name;
echo $group->description;
```

Create group

```php
$group = $sdk->createGroup([
    'name' => 'My New Group',
    'description' => 'Group description',
]);

echo $group->id;
```

Update group

```php
$sdk->updateGroup(1, [
    'name' => 'Updated Name',
]);
```

Delete group

```php
$sdk->deleteGroup(1);
```

## Group Members
List members (simple collection)

```php
$members = $sdk->members(1); // groupId

foreach ($members as $member) {
    echo $member->id . ' - ' . $member->role;
}
```

List members with pagination (JSON:API format)

```php
$response = $sdk->membersAll(1, [
    'status' => 'active',
    'role' => 'member',
], 1);

// Access data
foreach ($response['data'] as $member) {
    echo $member->role;
}

// Check pagination
if ($response['links']['next']) {
    echo "More pages available...";
}

// Access meta
echo $response['meta']['per_page'];
```

## Group Records
List records

```php
$records = $sdk->records(1); // groupId

foreach ($records as $record) {
    echo $record->type . ': ' . $record->duration;
}
```

Get single record
```php
$record = $sdk->record(1, 1); // groupId, recordId
```

Delete record
```php
$sdk->deleteRecord(1, 1);
```

## Record Aggregates
Get aggregates for all types
```php
$aggregates = $sdk->recordsAggregate(123, [
    'start' => '2024-03-01',
    'end' => '2024-03-31',
    'interval' => 'thisMonth',
    'users' => [1, 2, 3],
]);

// Works
foreach ($aggregates['works'] as $work) {
    echo $work->date . ': ' . $work->aggregate;
}

// Holidays
foreach ($aggregates['holidays'] as $holiday) {
    echo $holiday->date . ': ' . $holiday->aggregate;
}

// Vacations
foreach ($aggregates['vacations'] as $vacation) {
    echo $vacation->date . ': ' . $vacation->aggregate;
}

// Medicals
foreach ($aggregates['medicals'] as $medical) {
    echo $medical->date . ': ' . $medical->aggregate;
}
```

## Record Approvals
Approve a record

```php
$result = $sdk->approveGroupRecord(123, 456); // groupId, recordId

// Returns array with response from API
[
    'message' => 'Record updated successfully.',
    'received' => true,
    'status' => 'approve',
]

```

```php
$result = $sdk->disapproveGroupRecord(123, 456);
```

Check if update succeeded
```php
$response = $sdk->approveGroupRecord(123, 456);

if ($response['received']) {
    echo "Record approved successfully!";
} else {
    echo "Failed: " . $response['message'];
}
```