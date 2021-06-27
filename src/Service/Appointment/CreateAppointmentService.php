<?php


namespace App\Service\Appointment;


use App\Entity\Appointment;
use App\Repository\AppointmentRepository;
use App\Repository\EnterpriseRepository;
use App\Repository\ScheduleRepository;
use App\Repository\UserRepository;
use App\Service\FreeAppointment\FreeAppointmentService;
use App\Value\Date;

class CreateAppointmentService
{
    private UserRepository $userRepository;
    private ScheduleRepository $scheduleRepository;
    private EnterpriseRepository $enterpriseRepository;
    private FreeAppointmentService $freeAppointmentService;
    private AppointmentRepository $appointmentRepository;

    public function __construct(
        UserRepository $userRepository,
        ScheduleRepository $scheduleRepository,
        EnterpriseRepository $enterpriseRepository,
        FreeAppointmentService $freeAppointmentService,
        AppointmentRepository $appointmentRepository
    )
    {
        $this->userRepository = $userRepository;
        $this->scheduleRepository = $scheduleRepository;
        $this->enterpriseRepository = $enterpriseRepository;
        $this->freeAppointmentService = $freeAppointmentService;
        $this->appointmentRepository = $appointmentRepository;
    }

    public function create(string $userId, string $enterpriseId, string $scheduleId, Date $date, int $duration): Appointment
    {
        $user = $this->userRepository->findOneByIdOrFail($userId);
        $enterprise = $this->enterpriseRepository->findOneByIdOrFail($enterpriseId);
        $schedule = $this->scheduleRepository->findOneByIdAndEnterpriseIdOrFail($scheduleId, $enterprise->getId());

        $freeAppointments[] = $this->freeAppointmentService->getAvailableAppointment($date->getValue(), $schedule);

        $nextFreeAppointments = ceil($duration/$schedule->getIntervalTime());

        for($i=1; $i<$nextFreeAppointments; $i++){
            $dateInterval = new \DateInterval('PT' . $schedule->getIntervalTime() * $i . 'M');
            $freeAppointments[] = $this->freeAppointmentService->isAvailableAppointmentOrFail((clone $date->getValue())->add($dateInterval), $schedule);
        }

        $appointment = new Appointment($user, $enterprise, $schedule, $date->getValue(), $duration);
        $this->appointmentRepository->save($appointment);

        $this->freeAppointmentService->delete($freeAppointments);

        return $appointment;
    }
}