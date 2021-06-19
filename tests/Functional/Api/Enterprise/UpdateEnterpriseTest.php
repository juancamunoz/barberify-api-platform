<?php


namespace App\Tests\Functional\Api\Enterprise;


use Symfony\Component\HttpFoundation\JsonResponse;

class UpdateEnterpriseTest extends EnterpriseTestBase
{
    public function testUpdateEnterprise(): void
    {
        $payload = [
            'name' => 'testing name',
            'location' => 'testing location',
        ];

        self::$user->request(
            'PUT',
            sprintf('%s/%s', $this->endpoint, $this->getUserEnterpriseId()),
            [],
            [],
            [],
            json_encode($payload)
        );

        $response = self::$user->getResponse();
        $responseData = $this->getResponseData($response);

        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $this->assertEquals($payload['name'], $responseData['name']);
        $this->assertEquals($payload['location'], $responseData['location']);
    }

    public function testUpdateEnterpriseForAnotherUser(): void
    {
        $payload = [
            'name' => 'testing name',
            'location' => 'testing location',
        ];

        self::$peter->request(
            'PUT',
            sprintf('%s/%s', $this->endpoint, $this->getUserEnterpriseId()),
            [],
            [],
            [],
            json_encode($payload)
        );

        $response = self::$peter->getResponse();

        $this->assertEquals(JsonResponse::HTTP_FORBIDDEN, $response->getStatusCode());
    }
}