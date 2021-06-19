<?php


namespace App\Service\Enterprise;


use App\Entity\Enterprise;
use App\Repository\EnterpriseRepository;
use App\Service\File\FileService;
use Symfony\Component\HttpFoundation\Request;

class UploadAvatarService
{
    private FileService $fileService;
    private EnterpriseRepository $enterpriseRepository;

    public function __construct(FileService $fileService, EnterpriseRepository $enterpriseRepository)
    {
        $this->fileService = $fileService;
        $this->enterpriseRepository = $enterpriseRepository;
    }

    public function uploadAvatar(Request $request, Enterprise $enterprise): Enterprise
    {
        $file = $this->fileService->validateFile($request, FileService::ENTERPRISE_AVATAR);

        $this->fileService->deleteFile($enterprise->getAvatar());

        $avatar = $this->fileService->uploadFile($file, FileService::ENTERPRISE_AVATAR, 'public');

        $enterprise->setAvatar($avatar);

        $this->enterpriseRepository->save($enterprise);

        return $enterprise;
    }
}