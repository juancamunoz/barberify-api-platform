<?php


namespace App\Value;


use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class Date
{
    private \DateTime $value;

    public function __construct(string $value)
    {
        try {
            $this->value = new \DateTime($value);
        } catch (\Exception $e) {
            throw new BadRequestHttpException('Date is bad formatted');
        }
    }

    public function getValue(): \DateTime
    {
        return $this->value;
    }

}