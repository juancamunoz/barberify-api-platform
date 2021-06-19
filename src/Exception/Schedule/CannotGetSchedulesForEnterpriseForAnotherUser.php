<?php


namespace App\Exception\Schedule;


use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class CannotGetSchedulesForEnterpriseForAnotherUser extends AccessDeniedHttpException
{
    public function __construct()
    {
        parent::__construct('Can not get schedules for enterprise that user does not owns');
    }
}