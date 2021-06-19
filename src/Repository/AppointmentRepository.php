<?php


namespace App\Repository;


use App\Entity\Appointment;

class AppointmentRepository extends BaseRepository
{

    protected static function entityClass(): string
    {
        return Appointment::class;
    }

    public function save(Appointment $appointment): void
    {
        $this->saveEntity($appointment);
    }
}