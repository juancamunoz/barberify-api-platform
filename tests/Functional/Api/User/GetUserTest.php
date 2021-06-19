<?php

declare(strict_types=1);

namespace App\Tests\Functional\Api\User;

use Symfony\Component\HttpFoundation\JsonResponse;

class GetUserTest extends UserTestBase
{
    public function testGetUserWithAdmin(): void
    {
        $userId = $this->getUserId();

        self::$admin->request('GET', \sprintf('%s/%s', $this->endpoint, $userId));

        $response = self::$admin->getResponse();
        $responseData = $this->getResponseData($response);

        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $this->assertEquals($userId, $responseData['id']);
    }

    public function testGetAdminWithUser(): void
    {
        self::$user->request('GET', \sprintf('%s/%s', $this->endpoint, $this->getAdminId()));

        $response = self::$user->getResponse();

        $this->assertEquals(JsonResponse::HTTP_FORBIDDEN, $response->getStatusCode());
    }
}
