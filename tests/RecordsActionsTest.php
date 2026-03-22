<?php

namespace Zaimea\SDK\Groups\Tests;

use Zaimea\SDK\Groups\SDK;
use Zaimea\SDK\Groups\Resources\Record;
use Zaimea\SDK\Groups\Resources\Resource;
use Zaimea\SDK\Groups\Resources\Response;

class RecordsActionsTest extends TestCase
{
    private function createSDKWithMock(array $responses): SDK
    {
        $httpClient = $this->mockHttpClient($responses);
        return new SDK('test_key', $httpClient);
    }

    public function test_can_get_record()
    {
        $sdk = $this->createSDKWithMock([
            $this->mockResponse([
                'data' => [
                    'id' => 456,
                    'user_id' => 789,
                    'project_id' => 111,
                    'start' => '2026-03-22 08:00:00',
                    'end' => '2026-03-22 17:00:00',
                    'duration' => '08:00:00',
                ]
            ])
        ]);

        $record = $sdk->record(123, 456);

        $this->assertInstanceOf(Record::class, $record);
        $this->assertEquals('08:00:00', $record->duration);
    }

    public function test_can_list_records()
    {
        $sdk = $this->createSDKWithMock([
            $this->mockResponse([
                'records' => [
                    ['id' => 1, 'duration' => '08:00:00'],
                    ['id' => 2, 'duration' => '07:30:00'],
                ]
            ])
        ]);

        $records = $sdk->records(123, [
            'start' => '2026-03-01',
            'end' => '2026-03-31',
        ], 1);

        $this->assertCount(2, $records);
        $this->assertInstanceOf(Record::class, $records[0]);
    }

    public function test_can_get_records_aggregate()
    {
        $sdk = $this->createSDKWithMock([
            $this->mockResponse([
                'works' => [
                    'data' => [
                        ['id' => 1, 'duration' => '40:00:00'],
                    ]
                ],
                'holidays' => [
                    'data' => [
                        ['id' => 1, 'title' => 'Public Holiday'],
                    ]
                ],
                'vacations' => ['data' => []],
                'medicals' => ['data' => []],
            ])
        ]);

        $aggregates = $sdk->recordsAggregate(123, [
            'interval' => 'thisMonth',
            'for' => 'projects',
        ]);

        $this->assertArrayHasKey('works', $aggregates);
        $this->assertArrayHasKey('holidays', $aggregates);
        $this->assertArrayHasKey('vacations', $aggregates);
        $this->assertArrayHasKey('medicals', $aggregates);
        
        $this->assertCount(1, $aggregates['works']);
        $this->assertCount(1, $aggregates['holidays']);
    }

    public function test_can_approve_record()
    {
        $sdk = $this->createSDKWithMock([
            $this->mockResponse([
                'status' => 'success',
                'message' => 'Record approved',
            ])
        ]);

        $response = $sdk->approveRecord(123, 456);

        $this->assertInstanceOf(Resource::class, $response);
    }

    public function test_can_disapprove_record()
    {
        $sdk = $this->createSDKWithMock([
            $this->mockResponse([
                'status' => 'success',
                'message' => 'Record disapproved',
            ])
        ]);

        $response = $sdk->disapproveRecord(123, 456);

        $this->assertInstanceOf(Resource::class, $response);
    }

    public function test_can_delete_record()
    {
        $sdk = $this->createSDKWithMock([
            $this->mockResponse([
                'status' => 'success',
                'message' => 'Record deleted',
            ])
        ]);

        $response = $sdk->deleteRecord(123, 456);

        $this->assertInstanceOf(Resource::class, $response);
    }
}