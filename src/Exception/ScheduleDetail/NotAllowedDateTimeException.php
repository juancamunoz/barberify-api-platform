<?php


namespace App\Exception\ScheduleDetail;


use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class NotAllowedDateTimeException extends BadRequestHttpException
{
    public function __construct()
    {
        parent::__construct('Only 2020-01-01 date are allowed');
    }
}