<?php

namespace Zaimea\SDK\Groups\Tests;

use Zaimea\SDK\Groups\SDK;
use Zaimea\SDK\Groups\Resources\Project;
use Zaimea\SDK\Groups\Resources\Response;

class ProjectsActionsTest extends TestCase
{
    private function createSDKWithMock(array $responses): SDK
    {
        $httpClient = $this->mockHttpClient($responses);
        return new SDK('test_key', $httpClient);
    }

    public function test_can_get_project()
    {
        $sdk = $this->createSDKWithMock([
            $this->mockResponse([
                'data' => [
                    'id' => 456,
                    'title' => 'Test Project',
                    'description' => 'Project description',
                    'status' => 1,
                ]
            ])
        ]);

        $project = $sdk->project(123, 456);

        $this->assertInstanceOf(Project::class, $project);
        $this->assertEquals('Test Project', $project->title);
    }

    public function test_can_list_projects()
    {
        $sdk = $this->createSDKWithMock([
            $this->mockResponse([
                'projects' => [
                    ['id' => 1, 'title' => 'Project 1'],
                    ['id' => 2, 'title' => 'Project 2'],
                ]
            ])
        ]);

        $projects = $sdk->projects(123, [], 1);

        $this->assertCount(2, $projects);
        $this->assertInstanceOf(Project::class, $projects[0]);
    }

    public function test_can_create_project()
    {
        $sdk = $this->createSDKWithMock([
            $this->mockResponse([
                'status' => 'success',
                'message' => 'Project created',
            ])
        ]);

        $response = $sdk->createProject(123, [
            'form' => [
                'title' => 'New Project',
                'description' => 'Description',
                'users' => [1, 2],
                'clients' => [],
                'tasks' => [],
                'templates' => [],
                'work_pause' => [
                    'work' => ['time_1' => '08:00', 'time_2' => '12:00'],
                    'pause' => ['time_1' => '12:00', 'time_2' => '13:00'],
                ],
                'status' => 1,
            ]
        ]);

        $this->assertInstanceOf(Response::class, $response);
    }

    public function test_can_update_project()
    {
        $sdk = $this->createSDKWithMock([
            $this->mockResponse([
                'status' => 'success',
                'message' => 'Project updated',
            ])
        ]);

        $response = $sdk->updateProject(123, 456, [
            'form' => [
                'title' => 'Updated Title',
                'description' => 'Updated description',
            ]
        ]);

        $this->assertInstanceOf(Response::class, $response);
    }

    public function test_can_delete_project()
    {
        $sdk = $this->createSDKWithMock([
            $this->mockResponse([
                'status' => 'success',
                'message' => 'Project deleted',
            ])
        ]);

        $response = $sdk->deleteProject(123, 456);

        $this->assertInstanceOf(Response::class, $response);
    }

    public function test_can_get_project_relations()
    {
        $sdk = $this->createSDKWithMock([
            $this->mockResponse(['clients' => [['id' => 1, 'name' => 'Client 1']]]),
            $this->mockResponse(['tasks' => [['id' => 1, 'title' => 'Task 1']]]),
            $this->mockResponse(['users' => [['id' => 1, 'name' => 'User 1']]]),
            $this->mockResponse(['templates' => [['id' => 1, 'title' => 'Template 1']]]),
        ]);

        $clients = $sdk->projectClients(123, 456);
        $tasks = $sdk->projectTasks(123, 456);
        $users = $sdk->projectUsers(123, 456);
        $templates = $sdk->projectTemplates(123, 456);

        $this->assertNotEmpty($clients);
        $this->assertNotEmpty($tasks);
        $this->assertNotEmpty($users);
        $this->assertNotEmpty($templates);
    }
}