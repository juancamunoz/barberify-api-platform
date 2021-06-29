<?php


namespace App\Api\Action\Appointment;


use App\Api\DTO\EditAppointmentDTO;
use App\Entity\Appointment;
use App\Entity\User;
use App\Service\Appointment\EditAppointmentService;

class Edit
{
    private EditAppointmentService $editAppointmentService;

    public function __construct(EditAppointmentService $editAppointmentService)
    {
        $this->editAppointmentService = $editAppointmentService;
    }

    public function __invoke(EditAppointmentDTO $appointmentDTO, User $user, string $id): Appointment
    {
        return $this->editAppointmentService->edit(
            $user->getId(),
            $id,
            $appointmentDTO->getDate(),
            $appointmentDTO->getDuration()
        );
    }
}