<?php


namespace App\Doctrine\Extension;


use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use App\Entity\Schedule;
use App\Entity\ScheduleDetail;
use App\Entity\User;
use App\Exception\Schedule\CannotGetSchedulesForEnterpriseForAnotherUser;
use App\Repository\ScheduleRepository;
use App\Security\Role;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Security;

class DoctrineScheduleExtension implements QueryCollectionExtensionInterface
{
    private TokenStorageInterface $tokenStorage;
    private Security $security;
    private ScheduleRepository $scheduleRepository;

    public function __construct(TokenStorageInterface $tokenStorage, Security $security, ScheduleRepository $scheduleRepository)
    {
        $this->tokenStorage = $tokenStorage;
        $this->security = $security;
        $this->scheduleRepository = $scheduleRepository;
    }

    public function applyToCollection(
        QueryBuilder $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
        string $resourceClass,
        string $operationName = null
    ) {
        $this->addWhere($queryBuilder, $resourceClass);
    }

    private function addWhere(QueryBuilder $qb, string $resourceClass): void
    {
        if ($this->security->isGranted(Role::ROLE_ADMIN)) {
            return;
        }

        /** @var User|null $tokenUser */
        $tokenUser = $this->tokenStorage->getToken()
            ? $this->tokenStorage->getToken()->getUser()
            : null;

        if(ScheduleDetail::class === $resourceClass){
            /** @var Schedule $schedule */
            $scheduleId = $qb->getParameters()->first()->getValue();

            $schedule = $this->scheduleRepository->findOneByIdOrFail($scheduleId);
            if(!$schedule->getEnterprise()->isOwnedBy($tokenUser)){
                throw new CannotGetSchedulesForEnterpriseForAnotherUser();
            }
        }

    }
}