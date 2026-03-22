<?php

namespace Zaimea\SDK\Groups\Tests;

use Zaimea\SDK\Groups\SDK;
use Zaimea\SDK\Groups\Resources\Group;
use Zaimea\SDK\Groups\Resources\GroupMount;
use Zaimea\SDK\Groups\Resources\Response;

class GroupsActionsTest extends TestCase
{
    private function createSDKWithMock(array $responses): SDK
    {
        $httpClient = $this->mockHttpClient($responses);
        return new SDK('test_key', $httpClient);
    }

    public function test_can_get_group()
    {
        $sdk = $this->createSDKWithMock([
            $this->mockResponse([
                'data' => [
                    'id' => 123,
                    'name' => 'Test Group',
                    'status' => 1,
                ]
            ])
        ]);

        $group = $sdk->group(123);

        $this->assertInstanceOf(Group::class, $group);
        $this->assertEquals(123, $group->groupId);
        $this->assertEquals('Test Group', $group->name);
    }

    public function test_can_list_groups()
    {
        $sdk = $this->createSDKWithMock([
            $this->mockResponse([
                'groups' => [
                    ['id' => 1, 'name' => 'Group 1', 'status' => 1],
                    ['id' => 2, 'name' => 'Group 2', 'status' => 1],
                ]
            ])
        ]);

        $groups = $sdk->groups();

        $this->assertCount(2, $groups);
        $this->assertInstanceOf(Group::class, $groups[0]);
        $this->assertEquals('Group 1', $groups[0]->name);
        $this->assertEquals('Group 2', $groups[1]->name);
    }

    public function test_can_mount_group()
    {
        $sdk = $this->createSDKWithMock([
            $this->mockResponse([
                'data' => [
                    'group' => ['id' => 123, 'name' => 'Test'],
                    'plugins' => ['plugin1', 'plugin2'],
                    'configs' => ['config1'],
                    'plugins_selected' => ['plugin1'],
                    'configs_selected' => ['config1'],
                    'project_required' => true,
                ]
            ])
        ]);

        $mount = $sdk->mountGroup(123);

        $this->assertInstanceOf(GroupMount::class, $mount);
        $this->assertEquals(123, $mount->groupId);
        $this->assertTrue($mount->projectRequired);
        $this->assertEquals(['plugin1', 'plugin2'], $mount->plugins);
    }

    public function test_can_set_current_group()
    {
        $sdk = $this->createSDKWithMock([
            $this->mockResponse([
                'status' => 'success',
                'message' => 'Current group updated',
            ])
        ]);

        $response = $sdk->setCurrentGroup(123);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals('success', $response->status);
    }

    public function test_can_create_group()
    {
        $sdk = $this->createSDKWithMock([
            $this->mockResponse([
                'group' => [
                    'id' => 123,
                    'name' => 'New Group',
                    'status' => 1,
                ]
            ]),
            $this->mockResponse([
                'data' => [
                    'id' => 123,
                    'name' => 'New Group',
                    'status' => 1,
                ]
            ])
        ]);

        $group = $sdk->createGroup(['name' => 'New Group']);

        $this->assertInstanceOf(Group::class, $group);
        $this->assertEquals('New Group', $group->name);
    }

    public function test_can_update_group()
    {
        $sdk = $this->createSDKWithMock([
            $this->mockResponse([
                'status' => 'success',
                'message' => 'Group updated',
            ])
        ]);

        $response = $sdk->updateGroup(123, [
            'name' => 'Updated Name',
            'description' => 'New description',
        ]);

        $this->assertInstanceOf(Response::class, $response);
    }

    public function test_can_update_group_details()
    {
        $sdk = $this->createSDKWithMock([
            $this->mockResponse([
                'status' => 'success',
                'message' => 'Details updated',
            ])
        ]);

        $response = $sdk->updateGroupDetails(123, [
            'phone' => '+49 123 456789',
            'city' => 'Berlin',
        ]);

        $this->assertInstanceOf(Response::class, $response);
    }

    public function test_can_update_group_settings()
    {
        $sdk = $this->createSDKWithMock([
            $this->mockResponse([
                'status' => 'success',
                'message' => 'Settings updated',
            ])
        ]);

        $response = $sdk->updateGroupSettings(123, [
            'lang' => 'en',
            'time_format' => '24h',
        ]);

        $this->assertInstanceOf(Response::class, $response);
    }

    public function test_can_transfer_group()
    {
        $sdk = $this->createSDKWithMock([
            $this->mockResponse([
                'status' => 'success',
                'message' => 'Group transferred',
            ])
        ]);

        $response = $sdk->transferGroup(123, [
            'password' => 'current_password',
            'email' => 'newowner@example.com',
        ]);

        $this->assertInstanceOf(Response::class, $response);
    }

    public function test_can_delete_group()
    {
        $sdk = $this->createSDKWithMock([
            $this->mockResponse([
                'status' => 'success',
                'message' => 'Group deleted',
            ])
        ]);

        $response = $sdk->deleteGroup(123);

        $this->assertInstanceOf(Response::class, $response);
    }
}