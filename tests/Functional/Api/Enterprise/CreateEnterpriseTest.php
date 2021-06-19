<?php


namespace App\Tests\Functional\Api\Enterprise;


use Symfony\Component\HttpFoundation\JsonResponse;

class CreateEnterpriseTest extends EnterpriseTestBase
{
    public function testCreateEnterprise(): void
    {
        $payload = [
            'name' => 'testing name',
            'location' => 'testing location',
            'owner' => sprintf('/api/v1/users/%s', $this->getUserId())
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
        $this->assertEquals($payload['name'], $responseData['name']);
    }

    public function testCreateEnterpriseWithNoName(): void
    {
        $payload = [
            'location' => 'testing location',
            'owner' => sprintf('/api/v1/users/%s', $this->getUserId())
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

    public function testCreateEnterpriseWithNoLocation(): void
    {
        $payload = [
            'name' => 'asdasd',
            'owner' => sprintf('/api/v1/users/%s', $this->getUserId())
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

    public function testCreateEnterpriseWithNoOwner(): void
    {
        $payload = [
            'name' => 'asdasd',
            'location' => 'asdasd'
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

    public function testCreateEnterpriseForAnotherUser(): void
    {
        $payload = [
            'name' => 'testing name',
            'location' => 'testing location',
            'owner' => sprintf('/api/v1/users/%s', $this->getUserId())
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
}