<?php


namespace App\Tests\Functional\Api\ScheduleDetail;


use Symfony\Component\HttpFoundation\JsonResponse;

class CreateScheduleDetailTest extends ScheduleDetailTestBase
{
    public function testCreateScheduleDetail(): void
    {
        $payload = [
            'schedule' => sprintf('/api/v1/schedules/%s', $this->getUserEnterpriseScheduleId()),
            'day' => 'monday',
            'startHour' => (new \DateTime('2020-01-01 14:00:00'))->format('Y-m-d H:i:s'),
            'endHour' => (new \DateTime('2020-01-01 21:00:00'))->format('Y-m-d H:i:s')
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
        $this->assertEquals($payload['day'], $responseData['day']);
    }

    public function testCreateScheduleDetailForAnotherUser(): void
    {
        $payload = [
            'schedule' => sprintf('/api/v1/schedules/%s', $this->getUserEnterpriseScheduleId()),
            'day' => 'monday',
            'startHour' => (new \DateTime('2020-01-01 14:00:00'))->format('Y-m-d H:i:s'),
            'endHour' => (new \DateTime('2020-01-01 21:00:00'))->format('Y-m-d H:i:s')
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

    public function testCreateScheduleDetailWithWrongDayDates(): void
    {
        $payload = [
            'schedule' => sprintf('/api/v1/schedules/%s', $this->getUserEnterpriseScheduleId()),
            'day' => 'monday',
            'startHour' => (new \DateTime('2020-01-02 14:00:00'))->format('Y-m-d H:i:s'),
            'endHour' => (new \DateTime('2020-01-02 21:00:00'))->format('Y-m-d H:i:s')
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

    public function testCreateScheduleDetailWithWrongDayName(): void
    {
        $payload = [
            'schedule' => sprintf('/api/v1/schedules/%s', $this->getUserEnterpriseScheduleId()),
            'day' => 'invalid-day-name',
            'startHour' => (new \DateTime('2020-01-02 14:00:00'))->format('Y-m-d H:i:s'),
            'endHour' => (new \DateTime('2020-01-02 21:00:00'))->format('Y-m-d H:i:s')
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

    public function testCreateScheduleDetailWithWrongStartAndHourDates(): void
    {
        $payload = [
            'schedule' => sprintf('/api/v1/schedules/%s', $this->getUserEnterpriseScheduleId()),
            'day' => 'monday',
            'startHour' => (new \DateTime('2020-01-01 08:10:00'))->format('Y-m-d H:i:s'),
            'endHour' => (new \DateTime('2020-01-01 21:00:00'))->format('Y-m-d H:i:s')
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