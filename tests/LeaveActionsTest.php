<?php

namespace Zaimea\SDK\Groups\Tests;

use Zaimea\SDK\Groups\SDK;
use Zaimea\SDK\Groups\Resources\Holiday;
use Zaimea\SDK\Groups\Resources\Record;
use Zaimea\SDK\Groups\Resources\Response;

class LeaveActionsTest extends TestCase
{
    private function createSDKWithMock(array $responses): SDK
    {
        $httpClient = $this->mockHttpClient($responses);
        return new SDK('test_key', $httpClient);
    }

    public function test_can_create_vacation()
    {
        $sdk = $this->createSDKWithMock([
            $this->mockResponse([
                'status' => 'success',
                'message' => 'Vacation created',
            ])
        ]);

        $response = $sdk->createVacation(123, [
            'title' => 'Summer Vacation',
            'notes' => 'Family trip',
            'dates' => '2026-07-01, 2026-07-15',
        ]);

        $this->assertInstanceOf(Response::class, $response);
    }

    public function test_can_get_vacation()
    {
        $sdk = $this->createSDKWithMock([
            $this->mockResponse([
                'data' => [
                    'id' => 456,
                    'title' => 'Summer Vacation',
                    'dates' => '2026-07-01, 2026-07-15',
                ]
            ])
        ]);

        $vacation = $sdk->vacation(123, 456);

        $this->assertInstanceOf(Record::class, $vacation);
    }

    public function test_can_approve_vacation()
    {
        $sdk = $this->createSDKWithMock([
            $this->mockResponse([
                'status' => 'success',
                'message' => 'Vacation approved',
            ])
        ]);

        $response = $sdk->approveVacation(123, 456);

        $this->assertInstanceOf(Response::class, $response);
    }

    public function test_can_delete_vacation()
    {
        $sdk = $this->createSDKWithMock([
            $this->mockResponse([
                'status' => 'success',
                'message' => 'Vacation deleted',
            ])
        ]);

        $response = $sdk->deleteVacation(123, 456);

        $this->assertInstanceOf(Response::class, $response);
    }

    public function test_can_create_holiday()
    {
        $sdk = $this->createSDKWithMock([
            $this->mockResponse([
                'status' => 'success',
                'message' => 'Holiday created',
            ])
        ]);

        $response = $sdk->createHoliday(123, [
            'title' => 'Christmas',
            'description' => 'red',
            'users' => ['1', '2'],
            'dates' => '2026-12-25',
        ]);

        $this->assertInstanceOf(Response::class, $response);
    }

    public function test_can_get_holiday()
    {
        $sdk = $this->createSDKWithMock([
            $this->mockResponse([
                'data' => [
                    'id' => 456,
                    'title' => 'Public Holiday',
                    'description' => 'red',
                ]
            ])
        ]);

        $holiday = $sdk->holiday(123, 456);

        $this->assertInstanceOf(Holiday::class, $holiday);
    }

    public function test_can_create_medical()
    {
        $sdk = $this->createSDKWithMock([
            $this->mockResponse([
                'status' => 'success',
                'message' => 'Medical record created',
            ])
        ]);

        $response = $sdk->createMedical(123, [
            'title' => 'Doctor Appointment',
            'notes' => 'Annual checkup',
            'dates' => '2026-03-22',
        ]);

        $this->assertInstanceOf(Response::class, $response);
    }

    public function test_can_approve_medical()
    {
        $sdk = $this->createSDKWithMock([
            $this->mockResponse([
                'status' => 'success',
                'message' => 'Medical approved',
            ])
        ]);

        $response = $sdk->approveMedical(123, 456);

        $this->assertInstanceOf(Response::class, $response);
    }
}