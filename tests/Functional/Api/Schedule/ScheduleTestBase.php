<?php


namespace App\Tests\Functional\Api\Schedule;


use App\Tests\Functional\TestBase;

class ScheduleTestBase extends TestBase
{
    protected string $endpoint;

    public function setUp(): void
    {
        parent::setUp();
        $this->endpoint = '/api/v1/schedules';
    }
}