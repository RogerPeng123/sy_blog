<?php


namespace App\Messenger;


class ChangeAmountMessenger
{
    /**
     * @var float
     */
    private $money;

    public function __construct(float $money)
    {
        $this->money = $money;
    }

    /**
     * @return float
     */
    public function getMoney(): float
    {
        return $this->money;
    }
}