<?php

namespace Zaimea\SDK\Groups\Tests;

use Zaimea\SDK\Groups\SDK;
use Zaimea\SDK\Groups\Resources\Client;
use Zaimea\SDK\Groups\Resources\ClientMember;
use Zaimea\SDK\Groups\Resources\Response;

class ClientsActionsTest extends TestCase
{
    private function createSDKWithMock(array $responses): SDK
    {
        $httpClient = $this->mockHttpClient($responses);
        return new SDK('test_key', $httpClient);
    }

    public function test_can_get_client()
    {
        $sdk = $this->createSDKWithMock([
            $this->mockResponse([
                'data' => [
                    'id' => 456,
                    'client_name' => 'Acme Corporation',
                    'client_adress' => '123 Business St',
                    'status' => 1,
                ]
            ])
        ]);

        $client = $sdk->client(123, 456);

        $this->assertInstanceOf(Client::class, $client);
        $this->assertEquals('Acme Corporation', $client->clientName);
    }

    public function test_can_list_clients()
    {
        $sdk = $this->createSDKWithMock([
            $this->mockResponse([
                'clients' => [
                    ['id' => 1, 'client_name' => 'Client 1'],
                    ['id' => 2, 'client_name' => 'Client 2'],
                ]
            ])
        ]);

        $clients = $sdk->clients(123, [], 1);

        $this->assertCount(2, $clients);
        $this->assertInstanceOf(Client::class, $clients[0]);
    }

    public function test_can_create_client()
    {
        $sdk = $this->createSDKWithMock([
            $this->mockResponse([
                'status' => 'success',
                'message' => 'Client created',
            ])
        ]);

        $response = $sdk->createClient(123, [
            'client_name' => 'New Client',
            'client_adress' => '456 New Street',
            'projectBinds' => [1, 2],
            'status' => 1,
        ]);

        $this->assertInstanceOf(Response::class, $response);
    }

    public function test_can_update_client()
    {
        $sdk = $this->createSDKWithMock([
            $this->mockResponse([
                'status' => 'success',
                'message' => 'Client updated',
            ])
        ]);

        $response = $sdk->updateClient(123, 456, [
            'client_name' => 'Updated Client',
            'projects' => '1,2,3',
        ]);

        $this->assertInstanceOf(Response::class, $response);
    }

    public function test_can_delete_client()
    {
        $sdk = $this->createSDKWithMock([
            $this->mockResponse([
                'status' => 'success',
                'message' => 'Client deleted',
            ])
        ]);

        $response = $sdk->deleteClient(123, 456);

        $this->assertInstanceOf(Response::class, $response);
    }

    public function test_can_list_client_members()
    {
        $sdk = $this->createSDKWithMock([
            $this->mockResponse([
                'members' => [
                    ['id' => 1, 'user_id' => 10, 'role' => 'client'],
                    ['id' => 2, 'user_id' => 20, 'role' => 'client_admin'],
                ]
            ])
        ]);

        $members = $sdk->clientMembers(123, [], 1);

        $this->assertCount(2, $members);
        $this->assertInstanceOf(ClientMember::class, $members[0]);
    }

    public function test_can_create_client_member()
    {
        $sdk = $this->createSDKWithMock([
            $this->mockResponse([
                'status' => 'success',
                'message' => 'Client member added',
            ])
        ]);

        $response = $sdk->createClientMember(123, [
            'email' => 'client@example.com',
            'role' => 'client',
            'clientId' => 456,
            'status' => 1,
        ]);

        $this->assertInstanceOf(Response::class, $response);
    }

    public function test_can_remove_client_member()
    {
        $sdk = $this->createSDKWithMock([
            $this->mockResponse([
                'status' => 'success',
                'message' => 'Member removed',
            ])
        ]);

        $response = $sdk->removeClientMember(123, 789);

        $this->assertInstanceOf(Response::class, $response);
    }
}