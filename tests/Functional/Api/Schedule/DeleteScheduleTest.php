<?php


namespace App\Tests\Functional\Api\Schedule;


use Symfony\Component\HttpFoundation\JsonResponse;

class DeleteScheduleTest extends ScheduleTestBase
{
    public function testDeleteSchedule(): void
    {
        self::$user->request('DELETE', sprintf('%s/%s', $this->endpoint, $this->getUserEnterpriseScheduleId()));

        $response = self::$user->getResponse();

        $this->assertEquals(JsonResponse::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    public function testDeleteScheduleForAnotherUser(): void
    {
        self::$peter->request('DELETE', sprintf('%s/%s', $this->endpoint, $this->getUserEnterpriseScheduleId()));

        $response = self::$peter->getResponse();

        $this->assertEquals(JsonResponse::HTTP_FORBIDDEN, $response->getStatusCode());
    }
}