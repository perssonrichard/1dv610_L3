<?php

namespace hangmanModel;

class HangedMan
{
    private static $one =
    '
     
     
     
     
     
      
    
    -------------';

    private static $two =
    '
     |
     |
     |
     |
     |
     | 
    /|\
    -------------';

    private static $three =
    ' -------
     |/
     |
     |
     |
     |
     | 
    /|\
    -------------';

    private static $four =
    ' -------
     |/    | 
     |
     |
     |
     |
     | 
    /|\
    -------------';

    private static $five =
    ' -------
     |/    | 
     |     o
     |
     |
     |
     | 
    /|\
    -------------';

    private static $six =
    ' -------
     |/    | 
     |     o
     |     |
     |     |
     |
     | 
    /|\
    -------------';

    private static $seven =
    ' -------
     |/    | 
     |     o
     |     |
     |     |
     |    /
     | 
    /|\
    -------------';

    private static $eight =
    ' -------
     |/    | 
     |     o
     |     |
     |     |
     |    / \
     | 
    /|\
    -------------';

    private static $nine =
    ' -------
     |/    | 
     |     o
     |   --|
     |     |
     |    / \
     | 
    /|\
    -------------';

    private static $ten =
    ' -------
     |/    | 
     |     o
     |   --|--
     |     |
     |    / \
     | 
    /|\
    -------------';


    public function wrongGuess(int $guessNumber): string
    {
        switch ($guessNumber)
        {
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
