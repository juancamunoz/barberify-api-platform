<?php


namespace App\Api\Action\Enterprise;


use App\Entity\Enterprise;
use App\Entity\User;
use App\Exception\Enterprise\CannotUpdateEnterpriseAvatarForAnotherUserException;
use App\Repository\EnterpriseRepository;
use App\Service\Enterprise\UploadAvatarService;
use Symfony\Component\HttpFoundation\Request;

class UploadAvatar
{
    private UploadAvatarService $uploadAvatarService;
    private EnterpriseRepository $enterpriseRepository;

    public function __construct(UploadAvatarService $uploadAvatarService, EnterpriseRepository $enterpriseRepository)
    {
        $this->uploadAvatarService = $uploadAvatarService;
        $this->enterpriseRepository = $enterpriseRepository;
    }

    public function __invoke(Request $request, User $user, string $id): Enterprise
    {
        $enterprise = $this->enterpriseRepository->findOneByIdOrFail($id);

        if(!$enterprise->isOwnedBy($user)){
            throw new CannotUpdateEnterpriseAvatarForAnotherUserException();
        }

        return $this->uploadAvatarService->uploadAvatar($request, $enterprise);
    }
}