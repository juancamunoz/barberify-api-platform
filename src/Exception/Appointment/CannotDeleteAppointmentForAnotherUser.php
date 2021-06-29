<?php


namespace App\Exception\Appointment;


use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class CannotDeleteAppointmentForAnotherUser extends AccessDeniedHttpException
{
    public function __construct()
    {
        parent::__construct('You can not delete this appointment');
    }
}