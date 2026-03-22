<?php

namespace Zaimea\SDK\Groups\Tests;

use Zaimea\SDK\Groups\SDK;
use Zaimea\SDK\Groups\Resources\Resource;
use Zaimea\SDK\Groups\Resources\Group;
use Zaimea\SDK\Groups\Resources\User;
use Zaimea\SDK\Groups\Resources\Member;
use Zaimea\SDK\Groups\Resources\Client;
use Zaimea\SDK\Groups\Resources\Project;
use Zaimea\SDK\Groups\Resources\Record;

class ResourcesTest extends TestCase
{
    public function test_resource_fills_attributes()
    {
        $data = [
            'id' => 1,
            'name' => 'Test Name',
            'email' => 'test@example.com',
            'created_at' => '2026-03-22 10:00:00',
        ];

        $resource = new Resource($data, null);
        
        $this->assertEquals(1, $resource->id);
        $this->assertEquals('Test Name', $resource->name);
        $this->assertEquals('test@example.com', $resource->email);
        $this->assertEquals('2026-03-22 10:00:00', $resource->createdAt);
    }

    public function test_resource_camel_cases_attributes()
    {
        $data = [
            'user_id' => 1,
            'group_name' => 'Test Group',
            'created_at' => '2026-03-22',
        ];

        $resource = new Resource($data, null);
        
        $this->assertEquals(1, $resource->userId);
        $this->assertEquals('Test Group', $resource->groupName);
        $this->assertEquals('2026-03-22', $resource->createdAt);
    }

    public function test_user_resource()
    {
        $data = [
            'id' => 1,
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'can_create_groups' => true,
        ];

        $user = new User($data, null);
        
        $this->assertEquals(1, $user->id);
        $this->assertEquals('John Doe', $user->name);
        $this->assertEquals('john@example.com', $user->email);
        $this->assertTrue($user->canCreateGroups);
    }

    public function test_group_resource()
    {
        $data = [
            'group_id' => 123,
            'status' => 1,
            'created_at' => '2026-03-22 10:00:00',
        ];

        $group = new Group($data, null);
        
        $this->assertEquals(123, $group->groupId);
        $this->assertEquals(1, $group->status);
        $this->assertEquals('2026-03-22 10:00:00', $group->createdAt);
    }

    public function test_member_resource()
    {
        $data = [
            'group_id' => 123,
            'user_id' => 456,
            'role' => 'admin',
        ];

        $member = new Member($data, null);
        
        $this->assertEquals(123, $member->groupId);
        $this->assertEquals(456, $member->userId);
        $this->assertEquals('admin', $member->role);
    }

    public function test_client_resource()
    {
        $data = [
            'group_id' => 123,
            'client_name' => 'Acme Corp',
            'status' => 1,
        ];

        $client = new Client($data, null);
        
        $this->assertEquals(123, $client->groupId);
        $this->assertEquals('Acme Corp', $client->clientName);
    }

    public function test_project_resource()
    {
        $data = [
            'group_id' => 123,
            'project_id' => 456,
            'client_id' => 789,
            'title' => 'Test Project',
        ];

        $project = new Project($data, null);
        
        $this->assertEquals(123, $project->groupId);
        $this->assertEquals(456, $project->projectId);
        $this->assertEquals(789, $project->clientId);
    }

    public function test_record_resource()
    {
        $data = [
            'group_id' => 123,
            'record_id' => 456,
            'user_id' => 789,
            'duration' => '08:00:00',
        ];

        $record = new Record($data, null);
        
        $this->assertEquals(123, $record->groupId);
        $this->assertEquals(456, $record->recordId);
    }

    public function test_resource_transforms_collection()
    {
        $sdk = new SDK('test_key');
        
        // Use reflection to access protected method
        $reflection = new \ReflectionClass($sdk);
        $method = $reflection->getMethod('transformCollection');
        $method->setAccessible(true);
        
        $collection = [
            ['id' => 1, 'name' => 'First'],
            ['id' => 2, 'name' => 'Second'],
        ];

        $transformed = $method->invoke($sdk, $collection, Group::class);
        
        $this->assertCount(2, $transformed);
        $this->assertInstanceOf(Group::class, $transformed[0]);
        $this->assertInstanceOf(Group::class, $transformed[1]);
        $this->assertEquals('First', $transformed[0]->name);
        $this->assertEquals('Second', $transformed[1]->name);
    }

    public function test_resource_transforms_paginated_collection()
    {
        $sdk = new SDK('test_key');
        
        // Use reflection to access protected method
        $reflection = new \ReflectionClass($sdk);
        $method = $reflection->getMethod('transformCollection');
        $method->setAccessible(true);
        
        $collection = [
            'data' => [
                ['id' => 1, 'name' => 'First'],
                ['id' => 2, 'name' => 'Second'],
            ],
            'meta' => [
                'current_page' => 1,
                'last_page' => 5,
            ]
        ];

        $result = $method->invoke($sdk, $collection, Group::class);
        
        $this->assertArrayHasKey('data', $result);
        $this->assertArrayHasKey('meta', $result);
        $this->assertCount(2, $result['data']);
        $this->assertInstanceOf(Group::class, $result['data'][0]);
    }
}