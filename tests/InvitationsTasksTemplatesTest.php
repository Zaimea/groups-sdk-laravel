<?php

namespace Zaimea\SDK\Groups\Tests;

use Zaimea\SDK\Groups\SDK;
use Zaimea\SDK\Groups\Resources\Invitation;
use Zaimea\SDK\Groups\Resources\Task;
use Zaimea\SDK\Groups\Resources\Template;
use Zaimea\SDK\Groups\Resources\Response;

class InvitationsTasksTemplatesTest extends TestCase
{
    private function createSDKWithMock(array $responses): SDK
    {
        $httpClient = $this->mockHttpClient($responses);
        return new SDK('test_key', $httpClient);
    }

    public function test_can_accept_member_invitation()
    {
        $sdk = $this->createSDKWithMock([
            $this->mockResponse([
                'data' => [
                    'invitation_id' => 789,
                    'status' => 'accepted',
                    'group_id' => 123,
                ]
            ])
        ]);

        $invitation = $sdk->acceptMemberInvitation(789);

        $this->assertInstanceOf(Invitation::class, $invitation);
        $this->assertEquals(789, $invitation->invitationId);
    }

    public function test_can_delete_member_invitation()
    {
        $sdk = $this->createSDKWithMock([
            $this->mockResponse([
                'status' => 'success',
                'message' => 'Invitation deleted',
            ])
        ]);

        $response = $sdk->deleteMemberInvitation(789);

        $this->assertInstanceOf(Response::class, $response);
    }

    public function test_can_accept_client_invitation()
    {
        $sdk = $this->createSDKWithMock([
            $this->mockResponse([
                'data' => [
                    'id' => 789,
                    'status' => 'accepted',
                    'type' => 'client',
                ]
            ])
        ]);

        $invitation = $sdk->acceptClientInvitation(789);

        $this->assertInstanceOf(Invitation::class, $invitation);
    }

    public function test_can_get_task()
    {
        $sdk = $this->createSDKWithMock([
            $this->mockResponse([
                'data' => [
                    'id' => 456,
                    'title' => 'Test Task',
                    'description' => 'Task description',
                    'status' => 1,
                ]
            ])
        ]);

        $task = $sdk->task(123, 456);

        $this->assertInstanceOf(Task::class, $task);
        $this->assertEquals('Test Task', $task->title);
    }

    public function test_can_list_tasks()
    {
        $sdk = $this->createSDKWithMock([
            $this->mockResponse([
                'tasks' => [
                    ['id' => 1, 'title' => 'Task 1'],
                    ['id' => 2, 'title' => 'Task 2'],
                ]
            ])
        ]);

        $tasks = $sdk->tasks(123, [], 1);

        $this->assertCount(2, $tasks);
        $this->assertInstanceOf(Task::class, $tasks[0]);
    }

    public function test_can_create_task()
    {
        $sdk = $this->createSDKWithMock([
            $this->mockResponse([
                'status' => 'success',
                'message' => 'Task created',
            ])
        ]);

        $response = $sdk->createProjectTask(123, [
            'title' => 'New Task',
            'description' => 'Description',
            'projects' => [1, 2],
            'status' => 1,
        ]);

        $this->assertInstanceOf(Response::class, $response);
    }

    public function test_can_get_template()
    {
        $sdk = $this->createSDKWithMock([
            $this->mockResponse([
                'data' => [
                    'id' => 456,
                    'title' => 'Email Template',
                    'content' => 'Template content...',
                    'status' => 1,
                ]
            ])
        ]);

        $template = $sdk->template(123, 456);

        $this->assertInstanceOf(Template::class, $template);
        $this->assertEquals('Email Template', $template->title);
    }

    public function test_can_create_template()
    {
        $sdk = $this->createSDKWithMock([
            $this->mockResponse([
                'status' => 'success',
                'message' => 'Template created',
            ])
        ]);

        $response = $sdk->createProjectTemplate(123, [
            'title' => 'New Template',
            'description' => 'Description',
            'content' => 'Content here...',
            'projects' => [1],
            'status' => 1,
        ]);

        $this->assertInstanceOf(Response::class, $response);
    }

    public function test_can_update_template_projects()
    {
        $sdk = $this->createSDKWithMock([
            $this->mockResponse([
                'status' => 'success',
                'message' => 'Projects updated',
            ])
        ]);

        $response = $sdk->updateTemplateProjects(123, 456, [
            'projects' => ['1', '2', '3'],
        ]);

        $this->assertInstanceOf(Response::class, $response);
    }
}