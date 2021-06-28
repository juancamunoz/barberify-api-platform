<?php


namespace App\Tests\Functional\Api\Appointment;


use Symfony\Component\HttpFoundation\JsonResponse;

class CreateAppointmentTest extends AppointmentTestBase
{
    public function testCreateAppointment(): void
    {
        $payload = [
            'owner' => $this->getPeterId(),
            'enterprise' => $this->getUserEnterpriseId(),
            'schedule' => $this->getUserEnterpriseScheduleId(),
            'date' => '2021-06-14 10:00:00',
            'duration' => 30
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
        $responseData = $this->getResponseData($response);

        $this->assertEquals(JsonResponse::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals(strtotime($responseData['date']), strtotime($payload['date']));
    }

    public function testCreateAppointmentWithWrongUser(): void
    {
        $payload = [
            'owner' => 'wrong-id',
            'enterprise' => $this->getUserEnterpriseId(),
            'schedule' => $this->getUserEnterpriseScheduleId(),
            'date' => '2021-06-14 10:00:00',
            'duration' => 30
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
        $this->assertEquals(JsonResponse::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    public function testCreateAppointmentWithWrongEnterprise(): void
    {
        $payload = [
            'owner' => $this->getPeterId(),
            'enterprise' => 'wrong-enterprise',
            'schedule' => $this->getUserEnterpriseScheduleId(),
            'date' => '2021-06-14 10:00:00',
            'duration' => 30
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
        $this->assertEquals(JsonResponse::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    public function testCreateAppointmentWithWrongSchedule(): void
    {
        $payload = [
            'owner' => $this->getPeterId(),
            'enterprise' => $this->getUserEnterpriseId(),
            'schedule' => 'wrong-schedule',
            'date' => '2021-06-14 10:00:00',
            'duration' => 30
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
        $this->assertEquals(JsonResponse::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    public function testCreateAppointmentWithWrongDate(): void
    {
        $payload = [
            'owner' => $this->getPeterId(),
            'enterprise' => $this->getUserEnterpriseId(),
            'schedule' => $this->getUserEnterpriseScheduleId(),
            'date' => 'wrong-date',
            'duration' => 30
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
        $this->assertEquals(JsonResponse::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    public function testCreateAppointmentWithWrongDuration(): void
    {
        $payload = [
            'owner' => $this->getPeterId(),
            'enterprise' => $this->getUserEnterpriseId(),
            'schedule' => $this->getUserEnterpriseScheduleId(),
            'date' => '2021-06-14 10:00:00',
            'duration' => 'something-stupid'
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
        $this->assertEquals(JsonResponse::HTTP_BAD_REQUEST, $response->getStatusCode());
    }
}