<?php


namespace App\Api\Action\Appointment;


use App\Api\DTO\CreateAppointmentDTO;
use App\Entity\Appointment;
use App\Service\Appointment\CreateAppointmentService;

class Create
{
    private CreateAppointmentService $createAppointmentService;

    public function __construct(CreateAppointmentService $createAppointmentService)
    {
        $this->createAppointmentService = $createAppointmentService;
    }

    public function __invoke(CreateAppointmentDTO $createAppointmentDTO): Appointment
    {
        return $this->createAppointmentService->create(
            $createAppointmentDTO->getOwnerId(),
            $createAppointmentDTO->getEnterpriseId(),
            $createAppointmentDTO->getScheduleId(),
            $createAppointmentDTO->getDate(),
            $createAppointmentDTO->getDuration()
        );
    }
}