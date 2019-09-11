<?php


namespace App\Handler;

use App\Messenger\ChangeAmountMessenger;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class ChangeAmountMessengerHandler implements MessageHandlerInterface
{
    public function __construct()
    {

    }

    /**
     * 消息处理器
     * Author: roger peng
     * Time: 2019/9/11 15:14
     * @param ChangeAmountMessenger $messenger
     * @return string
     * @throws \Exception
     */
    public function __invoke(ChangeAmountMessenger $messenger)
    {
        return $this->changeAmount($messenger->getMoney());
    }

    /**
     * 转换成大写字
     * Author: roger peng
     * Time: 2019/9/11 15:13
     * @param float $num
     * @return string
     * @throws \Exception
     */
    private function changeAmount(float $num): string
    {
        $charCapital = '零壹贰叁肆伍陆柒捌玖';
        $charLowercase = '分角元拾佰仟万拾佰仟亿';
        $num = round($num, 2);
        $num = $num * 100;
        if (strlen($num) > 10) {
            throw new \Exception();
        }
        $i = 0;
        $c = "";
        while (1) {
            if ($i == 0) {
                $n = substr($num, strlen($num) - 1, 1);
            } else {
                $n = $num % 10;
            }
            $p1 = substr($charCapital, 3 * $n, 3);
            $p2 = substr($charLowercase, 3 * $i, 3);
            if ($n != '0' || ($n == '0' && ($p2 == '亿' || $p2 == '万' || $p2 == '元'))) {
                $c = $p1 . $p2 . $c;
            } else {
                $c = $p1 . $c;
            }
            $i = $i + 1;
            $num = $num / 10;
            $num = (int)$num;
            if ($num == 0) {
                break;
            }
        }
        $j = 0;
        $slen = strlen($c);
        while ($j < $slen) {
            $m = substr($c, $j, 6);
            if ($m == '零元' || $m == '零万' || $m == '零亿' || $m == '零零') {
                $left = substr($c, 0, $j);
                $right = substr($c, $j + 3);
                $c = $left . $right;
                $j = $j - 3;
                $slen = $slen - 3;
            }
            $j = $j + 3;
        }

        if (substr($c, strlen($c) - 3, 3) == '零') {
            $c = substr($c, 0, strlen($c) - 3);
        }
        if (empty($c)) {
            return "零元整";
        } else {
            $yan = mb_substr($c, -1, 1);
            if ($yan === '元') {
                $c .= '整';
            }
            return $c;
        }
    }
}
