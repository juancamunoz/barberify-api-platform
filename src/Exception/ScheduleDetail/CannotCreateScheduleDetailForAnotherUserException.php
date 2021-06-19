<?php


namespace App\Exception\ScheduleDetail;


use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class CannotCreateScheduleDetailForAnotherUserException extends AccessDeniedHttpException
{
    public function __construct()
    {
        parent::__construct('You can not create an schedule detail for an enterprise that you does not own');
    }
}