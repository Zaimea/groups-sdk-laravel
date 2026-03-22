<?php

namespace Zaimea\SDK\Groups\Tests;

use Zaimea\SDK\Groups\SDK;
use Zaimea\SDK\Groups\Resources\Role;
use Zaimea\SDK\Groups\Resources\Permission;
use Zaimea\SDK\Groups\Resources\Response;

class RolesActionsTest extends TestCase
{
    private function createSDKWithMock(array $responses): SDK
    {
        $httpClient = $this->mockHttpClient($responses);
        return new SDK('test_key', $httpClient);
    }

    public function test_can_get_role()
    {
        $sdk = $this->createSDKWithMock([
            $this->mockResponse([
                'data' => [
                    'id' => 456,
                    'name' => 'Admin',
                    'key' => 'admin',
                    'description' => 'Administrator role',
                ]
            ])
        ]);

        $role = $sdk->role(123, 456);

        $this->assertInstanceOf(Role::class, $role);
        $this->assertEquals('Admin', $role->name);
    }

    public function test_can_list_roles()
    {
        $sdk = $this->createSDKWithMock([
            $this->mockResponse([
                'roles' => [
                    ['id' => 1, 'name' => 'Admin', 'key' => 'admin'],
                    ['id' => 2, 'name' => 'Member', 'key' => 'member'],
                ]
            ])
        ]);

        $roles = $sdk->roles(123, [], 1);

        $this->assertCount(2, $roles);
        $this->assertInstanceOf(Role::class, $roles[0]);
    }

    public function test_can_create_role()
    {
        $sdk = $this->createSDKWithMock([
            $this->mockResponse([
                'status' => 'success',
                'message' => 'Role created',
            ])
        ]);

        $response = $sdk->createRole(123, [
            'client' => false,
            'name' => 'Manager',
            'description' => 'Department manager',
            'status' => true,
            'permissions' => ['view_records', 'approve_records'],
        ]);

        $this->assertInstanceOf(Response::class, $response);
    }

    public function test_can_get_role_permissions()
    {
        $sdk = $this->createSDKWithMock([
            $this->mockResponse([
                'permissions' => [
                    'group:calendar:track' => true,
                    'group:user:view_own' => false,
                ]
            ])
        ]);

        $permissions = $sdk->rolePermissions(123, 456);

        $this->assertIsArray($permissions);
        $this->assertArrayHasKey('permissions', $permissions);
        $this->assertArrayHasKey('group:calendar:track', $permissions['permissions']);
    }

    public function test_can_update_role_permissions()
    {
        $sdk = $this->createSDKWithMock([
            $this->mockResponse([
                'status' => 'success',
                'message' => 'Permissions updated',
            ])
        ]);

        $response = $sdk->updateRolePermissions(123, [
            'roleId' => 456,
            'permissions' => ['group:calendar:track'],
        ]);

        $this->assertInstanceOf(Response::class, $response);
    }

    public function test_can_list_group_permissions()
    {
        $sdk = $this->createSDKWithMock([
            $this->mockResponse([
                'permissions' => [
                    ['id' => 1, 'title' => 'View Records'],
                    ['id' => 2, 'title' => 'Edit Records'],
                ]
            ])
        ]);

        $permissions = $sdk->groupPermissions(123, [], 1);

        $this->assertCount(2, $permissions);
        $this->assertInstanceOf(Permission::class, $permissions[0]);
    }
}