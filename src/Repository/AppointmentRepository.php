<?php


namespace App\Repository;


use App\Entity\Appointment;
use App\Exception\Appointment\AppointmentNotFoundException;

class AppointmentRepository extends BaseRepository
{

    protected static function entityClass(): string
    {
        return Appointment::class;
    }

    public function findOneByIdOrFail(string $id): Appointment
    {
        if(null === $appointment = $this->objectRepository->find($id)){
            throw AppointmentNotFoundException::fromId($id);
        }

        return $appointment;
    }

    public function save(Appointment $appointment): void
    {
        $this->saveEntity($appointment);
    }

    public function remove(Appointment $appointment): void
    {
        $this->removeEntity($appointment);
    }
}