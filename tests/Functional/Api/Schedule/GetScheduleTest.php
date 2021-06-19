<?php


namespace App\Tests\Functional\Api\Schedule;


use Symfony\Component\HttpFoundation\JsonResponse;

class GetScheduleTest extends ScheduleTestBase
{
    public function testGetSchedule(): void
    {
        self::$user->request('GET', sprintf('%s/%s', $this->endpoint, $this->getUserEnterpriseScheduleId()));

        $response = self::$user->getResponse();

        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
    }

    public function testGetScheduleForEnterpriseUserNotOwns(): void
    {
        self::$peter->request('GET', sprintf('%s/%s', $this->endpoint, $this->getUserEnterpriseScheduleId()));

        $response = self::$peter->getResponse();

        $this->assertEquals(JsonResponse::HTTP_FORBIDDEN, $response->getStatusCode());
    }
}