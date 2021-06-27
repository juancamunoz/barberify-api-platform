<?php


namespace App\Api\Action\Appointment;


use App\Entity\Appointment;
use App\Service\Appointment\CreateAppointmentService;
use App\Service\Request\RequestService;
use App\Value\Date;
use App\Value\Number;
use Symfony\Component\HttpFoundation\Request;

class Create
{
    private CreateAppointmentService $createAppointmentService;

    public function __construct(CreateAppointmentService $createAppointmentService)
    {
        $this->createAppointmentService = $createAppointmentService;
    }

    public function __invoke(Request $request): Appointment
    {
        return $this->createAppointmentService->create(
            RequestService::getField($request, 'owner'),
            RequestService::getField($request, 'enterprise'),
            RequestService::getField($request, 'schedule'),
            new Date(RequestService::getField($request, 'date')),
            (new Number(RequestService::getField($request, 'duration')))->getNumber(),
        );
    }
}