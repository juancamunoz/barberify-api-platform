<?php


namespace App\Tests\Functional\Api\ScheduleDetail;


use App\Tests\Functional\TestBase;

class ScheduleDetailTestBase extends TestBase
{
    protected string $endpoint;

    public function setUp(): void
    {
        parent::setUp();

        $this->endpoint = '/api/v1/schedule_details';
    }
}