<?php


namespace App\Api\Listener\PreWrite;


use App\Entity\Enterprise;
use App\Entity\User;
use App\Exception\Enterprise\CannotCreateEnterpriseForAnotherUserException;
use App\Repository\UserRepository;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class EnterprisePreWriteListener implements PreWriteListener
{
    private const POST_ENTERPRISE = 'api_enterprises_post_collection';

    private TokenStorageInterface $tokenStorage;
    private UserRepository $userRepository;

    public function __construct(TokenStorageInterface $tokenStorage, UserRepository $userRepository)
    {
        $this->tokenStorage = $tokenStorage;
        $this->userRepository = $userRepository;
    }

    public function onKernelView(ViewEvent $event): void
    {
        $request = $event->getRequest();

        if(self::POST_ENTERPRISE === $request->get('_route')){

            /** @var User|null $tokenUser */
            $tokenUser = $this->tokenStorage->getToken()
                ? $this->tokenStorage->getToken()->getUser()
                : null;

            /** @var Enterprise $enterprise */
            $enterprise = $event->getControllerResult();

            if(!$tokenUser->equals($this->userRepository->findOneByIdOrFail($enterprise->getOwner()->getId()))){
                throw new CannotCreateEnterpriseForAnotherUserException();
            }
        }

    }
}