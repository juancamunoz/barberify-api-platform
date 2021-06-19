<?php


namespace App\Tests\Functional\Api\Schedule;


use Symfony\Component\HttpFoundation\JsonResponse;

class CreateScheduleTest extends ScheduleTestBase
{
    public function testCreateSchedule(): void
    {
        $payload = [
            'enterprise' => sprintf('/api/v1/enterprises/%s', $this->getUserEnterpriseId()),
            'dateFrom' => (new \DateTime())->format('Y-m-d H:i:s'),
            'dateTo' => ((new \DateTime())->add(new \DateInterval('P1M')))->format('Y-m-d H:i:s'),
            'intervalTime' => 15
        ];

        self::$user->request(
            'POST',
            sprintf('%s/create', $this->endpoint),
            [],
            [],
            [],
            json_encode($payload)
        );

        $response = self::$user->getResponse();
        $responseData = $this->getResponseData($response);

        $this->assertEquals(JsonResponse::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals($payload['intervalTime'], $responseData['intervalTime']);
    }

    public function testCreateScheduleForAnotherEnterprise(): void
    {
        $payload = [
            'enterprise' => sprintf('/api/v1/enterprises/%s', $this->getUserEnterpriseId()),
            'dateFrom' => (new \DateTime())->format('Y-m-d H:i:s'),
            'dateTo' => ((new \DateTime())->add(new \DateInterval('P1M')))->format('Y-m-d H:i:s'),
            'intervalTime' => 15
        ];

        self::$peter->request(
            'POST',
            sprintf('%s/create', $this->endpoint),
            [],
            [],
            [],
            json_encode($payload)
        );

        $response = self::$peter->getResponse();

        $this->assertEquals(JsonResponse::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    public function testCreateScheduleWithInvalidDates(): void
    {
        $payload = [
            'enterprise' => sprintf('/api/v1/enterprises/%s', $this->getUserEnterpriseId()),
            'dateFrom' => (new \DateTime())->format('Y-m-d H:i:s'),
            'dateTo' => (new \DateTime())->format('Y-m-d H:i:s'),
            'intervalTime' => 15
        ];

        self::$user->request(
            'POST',
            sprintf('%s/create', $this->endpoint),
            [],
            [],
            [],
            json_encode($payload)
        );

        $response = self::$user->getResponse();

        $this->assertEquals(JsonResponse::HTTP_BAD_REQUEST, $response->getStatusCode());
    }
}