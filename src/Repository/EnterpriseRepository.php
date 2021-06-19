<?php


namespace App\Repository;


use App\Entity\Enterprise;
use App\Exception\Enterprise\EnterpriseNotFoundException;

class EnterpriseRepository extends BaseRepository
{

    protected static function entityClass(): string
    {
        return Enterprise::class;
    }

    public function findOneByIdOrFail(string $id): Enterprise
    {
        if (null === $enterprise = $this->objectRepository->find($id)) {
            throw EnterpriseNotFoundException::fromId($id);
        }

        return $enterprise;
    }

    public function save(Enterprise $enterprise): void
    {
        $this->saveEntity($enterprise);
    }


}