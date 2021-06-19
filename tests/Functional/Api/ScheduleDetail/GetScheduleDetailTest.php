<?php


namespace App\Tests\Functional\Api\ScheduleDetail;


use Symfony\Component\HttpFoundation\JsonResponse;

class GetScheduleDetailTest extends ScheduleDetailTestBase
{
    public function testGetScheduleDetail(): void
    {
        self::$user->request('GET', sprintf('%s/%s', $this->endpoint, $this->getUserEnterpriseScheduleDetailId()));

        $response = self::$user->getResponse();

        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
    }

    public function testGetScheduleDetailForAnotherUser(): void
    {
        self::$peter->request('GET', sprintf('%s/%s', $this->endpoint, $this->getUserEnterpriseScheduleDetailId()));

        $response = self::$peter->getResponse();

        $this->assertEquals(JsonResponse::HTTP_FORBIDDEN, $response->getStatusCode());
    }
}