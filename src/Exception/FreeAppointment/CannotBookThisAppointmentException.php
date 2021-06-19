<?php


namespace App\Exception\FreeAppointment;


use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CannotBookThisAppointmentException extends BadRequestHttpException
{
    public function __construct()
    {
        parent::__construct('You can not book this appointment. Not enough free appointments after');
    }
}