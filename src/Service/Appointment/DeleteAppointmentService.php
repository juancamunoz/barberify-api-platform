<?php


namespace App\Service\Appointment;


use App\Entity\Appointment;
use App\Entity\FreeAppointment;
use App\Repository\AppointmentRepository;
use App\Service\FreeAppointment\FreeAppointmentService;

class DeleteAppointmentService
{
    private AppointmentRepository $appointmentRepository;
    private FreeAppointmentService $freeAppointmentService;

    public function __construct(AppointmentRepository $appointmentRepository, FreeAppointmentService $freeAppointmentService)
    {
        $this->appointmentRepository = $appointmentRepository;
        $this->freeAppointmentService = $freeAppointmentService;
    }

    public function delete(string $appointmentId): void
    {
        $appointment = $this->appointmentRepository->findOneByIdOrFail($appointmentId);

        $this->reCreateFreeAppointments($appointment);

        $this->appointmentRepository->remove($appointment);
    }

    private function reCreateFreeAppointments(Appointment $appointment): void
    {
        $schedule = $appointment->getSchedule();
        $numberOfAppointmentsTaken = ceil($appointment->getDuration() / $schedule->getIntervalTime());

        $dateTimeFormattedWithNoHours = new \DateTime($appointment->getDate()->format('Y-m-d'));

        $minutes = $appointment->getDate()->format('H:i:s');
        $startHour = new \DateTime(sprintf('%s %s', FreeAppointment::USED_DATE, $minutes));

        $minutesToAdd = new \DateInterval('PT' . $numberOfAppointmentsTaken * $schedule->getIntervalTime() . 'M');
        $endHour = (clone $startHour)->add($minutesToAdd);

        $this->freeAppointmentService->createFreeAppointmentsFromRange($dateTimeFormattedWithNoHours, $schedule, $startHour, $endHour);
    }
}