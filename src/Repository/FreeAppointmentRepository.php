<?php


namespace App\Repository;


use App\Entity\FreeAppointment;
use App\Exception\FreeAppointment\FreeAppointmentNotFound;

class FreeAppointmentRepository extends BaseRepository
{

    protected static function entityClass(): string
    {
        return FreeAppointment::class;
    }

    /**
     * @return array|FreeAppointment[]
     */
    public function findAllByDateAndSchedule(\DateTime $date, string $scheduleId): array
    {
        return $this->objectRepository->findBy(['date' => $date, 'schedule' => $scheduleId]);
    }

    public function findOneByDateAndStartHourAndScheduleOrFail(\DateTime $date, string $scheduleId): FreeAppointment
    {
        /** @var false|FreeAppointment $freeAppointment */
        if(!$freeAppointment = $this->executeFetchOneQuery(
                "SELECT * FROM free_appointment WHERE DATE_FORMAT(start_hour, '%H:%i') = ? AND schedule_id = ? AND date = ?",
                [$date->format('H:i'), $scheduleId, $date->format('Y-m-d')]
            )){
            throw FreeAppointmentNotFound::fromScheduleIdAndDate($scheduleId, $date->format('Y-m-d H:i'));
        }

        return $this->objectRepository->find($freeAppointment);
    }
}