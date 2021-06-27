<?php


namespace App\Tests\Functional\Api\Schedule;


use Symfony\Component\HttpFoundation\JsonResponse;

class GetScheduleDetailsTest extends ScheduleTestBase
{
    public function testGetScheduleDetails(): void
    {
        self::$user->request('GET', sprintf('%s/%s/details', $this->endpoint, $this->getUserEnterpriseScheduleId()));

        $response = self::$user->getResponse();
        $responseData = $this->getResponseData($response);

        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $this->assertCount(1, $responseData['hydra:member']);
    }

    public function testGetScheduleDetailsForAnotherUser(): void
    {
        self::$peter->request('GET', sprintf('%s/%s/details', $this->endpoint, $this->getUserEnterpriseScheduleId()));

        $response = self::$peter->getResponse();

        $this->assertEquals(JsonResponse::HTTP_FORBIDDEN, $response->getStatusCode());
    }
}