<?php

namespace hangmanModel;

class HangedMan
{
    private static $zero =
    '
     |/
     |
     |
     |
     |
     | 
    /|\
    -------------';

    private static $one =
    '     ________
     |/
     |
     |
     |
     |
     | 
    /|\
    -------------';

    private static $two =
    '     ________
     |/    | 
     |
     |
     |
     |
     | 
    /|\
    -------------';

    private static $three =
    '     ________
     |/    | 
     |     o
     |
     |
     |
     | 
    /|\
    -------------';

    private static $four =
    '     ________
     |/    | 
     |     o
     |     |
     |     |
     |
     | 
    /|\
    -------------';

    private static $five =
    '     ________
     |/    | 
     |     o
     |     |
     |     |
     |    /
     | 
    /|\
    -------------';

    private static $six =
    '     ________
     |/    | 
     |     o
     |     |
     |     |
     |    / \
     | 
    /|\
    -------------';

    private static $seven =
    '     ________
     |/    | 
     |     o
     |   --|
     |     |
     |    / \
     | 
    /|\
    -------------';

    private static $eight =
    '     ________
     |/    | 
     |     o
     |   --|--
     |     |
     |    / \
     | 
    /|\
    -------------';


    public function getHangedMan(int $guessNumber): string
    {
        switch ($guessNumber) {
            case 0:
                return self::$zero;

            case 1:
                return self::$one;

            case 2:
                return self::$two;

            case 3:
                return self::$three;

            case 4:
                return self::$four;

            case 5:
                return self::$five;

            case 6:
                return self::$six;

            case 7:
                return self::$seven;

            case 8:
                return self::$eight;

            case 9:
                return self::$nine;

            case 10:
                return self::$ten;
        }
    }
}
