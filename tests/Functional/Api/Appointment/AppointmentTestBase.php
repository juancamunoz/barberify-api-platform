<?php


namespace App\Tests\Functional\Api\Appointment;


use App\Tests\Functional\TestBase;

class AppointmentTestBase extends TestBase
{
    protected string $endpoint;

    public function setUp(): void
    {
        parent::setUp();
        $this->endpoint = '/api/v1/appointments';
    }
}