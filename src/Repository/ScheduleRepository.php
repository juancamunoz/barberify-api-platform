<?php


namespace App\Repository;


use App\Entity\Schedule;
use App\Exception\Schedule\ScheduleNotFoundException;

class ScheduleRepository extends BaseRepository
{

    protected static function entityClass(): string
    {
        return Schedule::class;
    }

    public function findOneByIdOrFail(string $id): Schedule
    {
        if(null === $schedule = $this->objectRepository->find($id)){
            throw ScheduleNotFoundException::fromId($id);
        }

        return $schedule;
    }

    public function findOneByIdAndEnterpriseIdOrFail(string $id, string $enterpriseId): Schedule
    {
        if(null === $schedule = $this->objectRepository->findOneBy(['id' => $id, 'enterprise' => $enterpriseId])){
            throw ScheduleNotFoundException::fromIdAndEnterpriseId($id, $enterpriseId);
        }

        return $schedule;
    }
}