<?php


namespace App\Tests\Functional\Api\Enterprise;



use Symfony\Component\HttpFoundation\JsonResponse;

class GetEnterpriseTest extends EnterpriseTestBase
{
    public function testGetEnterprise(): void
    {
        self::$user->request('GET', sprintf('%s/%s', $this->endpoint, $this->getUserEnterpriseId()));

        $response = self::$user->getResponse();
        $responseData = $this->getResponseData($response);

        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
    }

    public function testGetEnterpriseForAnotherUser(): void
    {
        self::$peter->request('GET', sprintf('%s/%s', $this->endpoint, $this->getUserEnterpriseId()));

        $response = self::$peter->getResponse();

        $this->assertEquals(JsonResponse::HTTP_FORBIDDEN, $response->getStatusCode());
    }
}