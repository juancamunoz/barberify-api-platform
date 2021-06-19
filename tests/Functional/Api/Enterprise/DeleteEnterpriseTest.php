<?php


namespace App\Tests\Functional\Api\Enterprise;


use Symfony\Component\HttpFoundation\JsonResponse;

class DeleteEnterpriseTest extends EnterpriseTestBase
{
    public function testDeleteEnterprise(): void
    {
        self::$user->request('DELETE', sprintf('%s/%s', $this->endpoint, $this->getUserEnterpriseId()));

        $response = self::$user->getResponse();

        $this->assertEquals(JsonResponse::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    public function testDeleteEnterpriseForAnotherUser(): void
    {
        self::$peter->request('DELETE', sprintf('%s/%s', $this->endpoint, $this->getUserEnterpriseId()));

        $response = self::$peter->getResponse();

        $this->assertEquals(JsonResponse::HTTP_FORBIDDEN, $response->getStatusCode());
    }
}