<?php


namespace App\Tests\Functional\Api\ScheduleDetail;


use Symfony\Component\HttpFoundation\JsonResponse;

class UpdateScheduleDetailTest extends ScheduleDetailTestBase
{
    public function testUpdateScheduleDetail(): void
    {
        $startHour = (new \DateTime('2020-01-01 16:00:00'))->format('Y-m-d H:i:s');
        $endHour = (new \DateTime('2020-01-01 20:00:00'))->format('Y-m-d H:i:s');

        $payload = [
            'startHour' => $startHour,
            'endHour' => $endHour
        ];

        self::$user->request(
            'PUT',
            sprintf('%s/%s', $this->endpoint, $this->getUserEnterpriseScheduleDetailId()),
            [],
            [],
            [],
            json_encode($payload)
        );

        $response = self::$user->getResponse();
        $responseData = $this->getResponseData($response);

        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());

        $startHourResponse = new \DateTime($responseData['startHour']);
        $this->assertEquals($startHour, $startHourResponse->format('Y-m-d H:i:s'));
    }

    public function testUpdateScheduleDetailForAnotherUser(): void
    {
        $startHour = (new \DateTime('2020-01-01 16:00:00'))->format('Y-m-d H:i:s');
        $endHour = (new \DateTime('2020-01-01 20:00:00'))->format('Y-m-d H:i:s');

        $payload = [
            'startHour' => $startHour,
            'endHour' => $endHour
        ];

        self::$peter->request(
            'PUT',
            sprintf('%s/%s', $this->endpoint, $this->getUserEnterpriseScheduleDetailId()),
            [],
            [],
            [],
            json_encode($payload)
        );

        $response = self::$peter->getResponse();

        $this->assertEquals(JsonResponse::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    public function testUpdateScheduleDetailWithWrongDate(): void
    {
        $startHour = (new \DateTime('2021-01-01 16:00:00'))->format('Y-m-d H:i:s');
        $endHour = (new \DateTime('2020-01-01 20:00:00'))->format('Y-m-d H:i:s');

        $payload = [
            'startHour' => $startHour,
            'endHour' => $endHour
        ];

        self::$user->request(
            'PUT',
            sprintf('%s/%s', $this->endpoint, $this->getUserEnterpriseScheduleDetailId()),
            [],
            [],
            [],
            json_encode($payload)
        );

        $response = self::$user->getResponse();

        $this->assertEquals(JsonResponse::HTTP_BAD_REQUEST, $response->getStatusCode());
    }
}