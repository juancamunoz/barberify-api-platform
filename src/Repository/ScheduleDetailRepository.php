<?php


namespace App\Repository;


use App\Entity\ScheduleDetail;

class ScheduleDetailRepository extends BaseRepository
{

    protected static function entityClass(): string
    {
        return ScheduleDetail::class;
    }

    /**
     * @return array|ScheduleDetail[]
     */
    public function findAllByScheduleIdAndDay(string $scheduleId, string $day): array
    {
        return $this->objectRepository->findBy([
            'schedule' => $scheduleId,
            'day' => $day
        ]);
    }
}