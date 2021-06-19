<?php


namespace App\Tests\Functional\Api\Enterprise;


use Symfony\Component\HttpFoundation\JsonResponse;

class GetEnterpriseSchedulesTest extends EnterpriseTestBase
{
    public function testGetEnterpriseSchedules(): void
    {
        self::$user->request('GET', sprintf('%s/%s/schedules', $this->endpoint, $this->getUserEnterpriseId()));

        $response = self::$user->getResponse();
        $responseData = $this->getResponseData($response);

        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $this->assertCount(1, $responseData['hydra:member']);
    }

    public function testGetEnterpriseSchedulesForAnotherUser(): void
    {
        self::$peter->request('GET', sprintf('%s/%s/schedules', $this->endpoint, $this->getUserEnterpriseId()));

        $response = self::$peter->getResponse();
        $responseData = $this->getResponseData($response);

        $this->assertEquals(JsonResponse::HTTP_FORBIDDEN, $response->getStatusCode());
    }
}