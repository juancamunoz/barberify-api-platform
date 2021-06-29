<?php


namespace App\Exception\Appointment;


use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class CannotEditAppointmentForAnotherUserException extends AccessDeniedHttpException
{
    public function __construct()
    {
        parent::__construct('You dont own this appointment');
    }
}