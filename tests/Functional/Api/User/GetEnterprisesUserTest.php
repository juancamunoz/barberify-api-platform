<?php


namespace App\Tests\Functional\Api\User;


use Symfony\Component\HttpFoundation\JsonResponse;

class GetEnterprisesUserTest extends UserTestBase
{

    public function testGetEnterprises(): void
    {
        self::$user->request('GET', sprintf('%s/%s/enterprises', $this->endpoint, $this->getUserId()));

        $response = self::$user->getResponse();
        $responseData = $this->getResponseData($response);


        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $this->assertCount(1, $responseData['hydra:member']);
    }

    public function testGetEnterprisesForAnotherUser(): void
    {
        self::$peter->request('GET', sprintf('%s/%s/enterprises', $this->endpoint, $this->getUserId()));

        $response = self::$peter->getResponse();
        $responseData = $this->getResponseData($response);


        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $this->assertCount(0, $responseData['hydra:member']);
    }
}