<?php


namespace App\Tests\Functional\Api\Schedule;


use Symfony\Component\HttpFoundation\JsonResponse;

class UpdateScheduleTest extends ScheduleTestBase
{
    public function testUpdateSchedule(): void
    {
        $payload = [
            'dateFrom' => (new \DateTime())->format('Y-m-d H:i:s'),
            'dateTo' => ((new \DateTime())->add(new \DateInterval('P1M')))->format('Y-m-d H:i:s'),
            'intervalTime' => 25
        ];

        self::$user->request(
            'PUT',
            sprintf('%s/%s', $this->endpoint, $this->getUserEnterpriseScheduleId()),
            [],
            [],
            [],
            json_encode($payload)
        );

        $response = self::$user->getResponse();
        $responseData = $this->getResponseData($response);

        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $this->assertEquals($payload['intervalTime'], $responseData['intervalTime']);
    }

    public function testUpdateScheduleForAnotherUserEnterprise(): void
    {
        $payload = [
            'dateFrom' => (new \DateTime())->format('Y-m-d H:i:s'),
            'dateTo' => ((new \DateTime())->add(new \DateInterval('P1M')))->format('Y-m-d H:i:s'),
            'intervalTime' => 25
        ];

        self::$peter->request(
            'PUT',
            sprintf('%s/%s', $this->endpoint, $this->getUserEnterpriseScheduleId()),
            [],
            [],
            [],
            json_encode($payload)
        );

        $response = self::$peter->getResponse();

        $this->assertEquals(JsonResponse::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    public function testUpdateScheduleWithWrongDates(): void
    {
        $payload = [
            'dateFrom' => (new \DateTime())->format('Y-m-d H:i:s'),
            'dateTo' => (new \DateTime())->format('Y-m-d H:i:s'),
            'intervalTime' => 25
        ];

        self::$user->request(
            'PUT',
            sprintf('%s/%s', $this->endpoint, $this->getUserEnterpriseScheduleId()),
            [],
            [],
            [],
            json_encode($payload)
        );

        $response = self::$user->getResponse();

        $this->assertEquals(JsonResponse::HTTP_BAD_REQUEST, $response->getStatusCode());
    }
}