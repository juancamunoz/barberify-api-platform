<?php


namespace App\Service\Appointment;


use App\Entity\Appointment;
use App\Exception\Appointment\CannotEditAppointmentForAnotherUserException;
use App\Exception\Appointment\CannotEditAppointmentToAnotherDayException;
use App\Repository\AppointmentRepository;
use App\Repository\FreeAppointmentRepository;

class EditAppointmentService
{
    private AppointmentRepository $appointmentRepository;
    private FreeAppointmentRepository $freeAppointmentRepository;
    private CreateAppointmentService $createAppointmentService;
    private DeleteAppointmentService $deleteAppointmentService;

    public function __construct(AppointmentRepository $appointmentRepository, FreeAppointmentRepository $freeAppointmentRepository, CreateAppointmentService $createAppointmentService, DeleteAppointmentService $deleteAppointmentService)
    {
        $this->appointmentRepository = $appointmentRepository;
        $this->freeAppointmentRepository = $freeAppointmentRepository;
        $this->createAppointmentService = $createAppointmentService;
        $this->deleteAppointmentService = $deleteAppointmentService;
    }

    public function edit(string $userId, string $appointmentId, \DateTime $newDate, int $duration): Appointment
    {
        $currentAppointment = $this->appointmentRepository->findOneByIdOrFail($appointmentId);

        if($currentAppointment->getOwner()->getId() !== $userId){
            throw new CannotEditAppointmentForAnotherUserException();
        }

        if($currentAppointment->getDate()->format('Y-m-d') !== $newDate->format('Y-m-d')){
            throw new CannotEditAppointmentToAnotherDayException();
        }

        $newAppointment = $this->createAppointmentService->create(
            $userId,
            $currentAppointment->getSchedule()->getEnterprise()->getId(),
            $currentAppointment->getSchedule()->getId(),
            $newDate,
            $duration
        );

        $this->deleteAppointmentService->delete($currentAppointment->getId(), $userId);

        return $newAppointment;
    }
}