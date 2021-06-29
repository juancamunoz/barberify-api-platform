<?php


namespace App\Exception\Appointment;


use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CannotEditAppointmentToAnotherDayException extends BadRequestHttpException
{
    public function __construct()
    {
        parent::__construct('You can not change the appointment date for another day. Just delete this.');
    }
}