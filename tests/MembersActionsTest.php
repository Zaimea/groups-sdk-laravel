<?php

namespace Zaimea\SDK\Groups\Tests;

use Zaimea\SDK\Groups\SDK;
use Zaimea\SDK\Groups\Resources\Member;
use Zaimea\SDK\Groups\Resources\Role;
use Zaimea\SDK\Groups\Resources\Response;

class MembersActionsTest extends TestCase
{
    private function createSDKWithMock(array $responses): SDK
    {
        $httpClient = $this->mockHttpClient($responses);
        return new SDK('test_key', $httpClient);
    }

    public function test_can_get_member()
    {
        $sdk = $this->createSDKWithMock([
            $this->mockResponse([
                'data' => [
                    'id' => 456,
                    'user_id' => 789,
                    'group_id' => 123,
                    'role' => 'member',
                ]
            ])
        ]);

        $member = $sdk->member(123, 456);

        $this->assertInstanceOf(Member::class, $member);
        $this->assertEquals(456, $member->id);
        $this->assertEquals('member', $member->role);
    }

    public function test_can_list_members()
    {
        $sdk = $this->createSDKWithMock([
            $this->mockResponse([
                'members' => [
                    ['id' => 1, 'user_id' => 10, 'role' => 'admin'],
                    ['id' => 2, 'user_id' => 20, 'role' => 'member'],
                ]
            ])
        ]);

        $members = $sdk->members(123, [], 1);

        $this->assertCount(2, $members);
        $this->assertInstanceOf(Member::class, $members[0]);
        $this->assertEquals('admin', $members[0]->role);
    }

    public function test_can_create_member()
    {
        $sdk = $this->createSDKWithMock([
            $this->mockResponse([
                'status' => 'success',
                'message' => 'Member created',
            ])
        ]);

        $response = $sdk->createMember(123, [
            'email' => 'new@example.com',
            'role' => 'member',
            'rate' => '08:00:00',
            'quota_percent' => 100,
            'working_days' => [
                'monday' => true,
                'tuesday' => true,
                'friday' => true,
            ],
            'status' => 1,
        ]);

        $this->assertInstanceOf(Response::class, $response);
    }

    public function test_can_update_member()
    {
        $sdk = $this->createSDKWithMock([
            $this->mockResponse([
                'status' => 'success',
                'message' => 'Member updated',
            ])
        ]);

        $response = $sdk->updateMember(123, [
            'memberId' => 456,
            'rate' => '07:00:00',
            'quota_percent' => 80,
        ]);

        $this->assertInstanceOf(Response::class, $response);
    }

    public function test_can_update_member_role()
    {
        $sdk = $this->createSDKWithMock([
            $this->mockResponse([
                'status' => 'success',
                'message' => 'Role updated',
            ])
        ]);

        $response = $sdk->updateMemberRole(123, [
            'memberId' => 456,
            'role' => 'admin',
        ]);

        $this->assertInstanceOf(Response::class, $response);
    }

    public function test_can_delete_member()
    {
        $sdk = $this->createSDKWithMock([
            $this->mockResponse([
                'status' => 'success',
                'message' => 'Member deleted',
            ])
        ]);

        $response = $sdk->deleteMember(123, 456);

        $this->assertInstanceOf(Response::class, $response);
    }

    public function test_can_list_member_roles()
    {
        $sdk = $this->createSDKWithMock([
            $this->mockResponse([
                'data' => [
                    ['id' => 1, 'name' => 'Admin', 'key' => 'admin'],
                    ['id' => 2, 'name' => 'Member', 'key' => 'member'],
                ]
            ])
        ]);

        $roles = $sdk->memberRoles(123, [], 1);

        $this->assertCount(2, $roles);
        $this->assertInstanceOf(Role::class, $roles[0]);
    }
}