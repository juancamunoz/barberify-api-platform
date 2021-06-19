<?php


namespace App\Tests\Functional\Api\Enterprise;


use App\Service\File\FileService;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;

class UploadEnterpriseAvatarTest extends EnterpriseTestBase
{
    public function testUploadEnterpriseAvatar(): void
    {
        $avatar = new UploadedFile(
            __DIR__ . '/../../../../fixtures/avatar.jpg',
            'avatar.jpg',
        );

        self::$user->request(
            'POST',
            sprintf('%s/%s/avatar', $this->endpoint, $this->getUserEnterpriseId()),
            [],
            [FileService::ENTERPRISE_AVATAR => $avatar]
        );

        $response = self::$user->getResponse();

        $this->assertEquals(JsonResponse::HTTP_CREATED, $response->getStatusCode());
    }

    public function testUploadEnterpriseAvatarWithWrongInputName(): void
    {
        $avatar = new UploadedFile(
            __DIR__ . '/../../../../fixtures/avatar.jpg',
            'avatar.jpg',
        );

        self::$user->request(
            'POST',
            sprintf('%s/%s/avatar', $this->endpoint, $this->getUserEnterpriseId()),
            [],
            ['invalid-input' => $avatar]
        );

        $response = self::$user->getResponse();

        $this->assertEquals(JsonResponse::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    public function testUploadEnterpriseAvatarForAnotherUser(): void
    {
        $avatar = new UploadedFile(
            __DIR__ . '/../../../../fixtures/avatar.jpg',
            'avatar.jpg',
        );

        self::$peter->request(
            'POST',
            sprintf('%s/%s/avatar', $this->endpoint, $this->getUserEnterpriseId()),
            [],
            ['invalid-input' => $avatar]
        );

        $response = self::$peter->getResponse();

        $this->assertEquals(JsonResponse::HTTP_FORBIDDEN, $response->getStatusCode());
    }
}