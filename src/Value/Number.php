<?php


namespace App\Value;


use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class Number
{
    private int $number;

    public function __construct($number)
    {
        if(!is_numeric($number)){
            throw new BadRequestHttpException('You have given a string, should be a number');
        }

        $this->number = $number;
    }

    public function getNumber(): int
    {
        return $this->number;
    }

}