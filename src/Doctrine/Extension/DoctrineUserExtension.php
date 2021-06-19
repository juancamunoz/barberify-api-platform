<?php

declare(strict_types=1);

namespace App\Doctrine\Extension;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use App\Entity\Enterprise;
use App\Entity\Schedule;
use App\Entity\User;
use App\Exception\Schedule\CannotGetSchedulesForEnterpriseForAnotherUser;
use App\Repository\EnterpriseRepository;
use App\Security\Role;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Security;

class DoctrineUserExtension implements QueryCollectionExtensionInterface
{
    private TokenStorageInterface $tokenStorage;
    private Security $security;
    private EnterpriseRepository $enterpriseRepository;

    public function __construct(TokenStorageInterface $tokenStorage, Security $security, EnterpriseRepository $enterpriseRepository)
    {
        $this->tokenStorage = $tokenStorage;
        $this->security = $security;
        $this->enterpriseRepository = $enterpriseRepository;
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

        $rootAlias = $qb->getRootAliases()[0];

        if(Enterprise::class === $resourceClass){
            $qb->andWhere(\sprintf('%s.%s = :user', $rootAlias, $this->getResources()[$resourceClass]));
            $qb->setParameter('user', $tokenUser);
        }

        if(Schedule::class === $resourceClass){
            $enterpriseId = $qb->getParameters()->first()->getValue();
            $enterprise = $this->enterpriseRepository->findOneByIdOrFail($enterpriseId);
            if(!$enterprise->isOwnedBy($tokenUser)){
                throw new CannotGetSchedulesForEnterpriseForAnotherUser();
            }
        }

    }

    private function getResources(): array
    {
        return [
            User::class => 'id',
            Enterprise::class => 'owner',
        ];
    }
}
