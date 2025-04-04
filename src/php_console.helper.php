<?php

    namespace PHPConsole;

    class PHPConsoleHelper{

        public static $defaultColor = 'white';
        public static $defaultBgColor = 'white';

        const C_DEFAULT = 'default';
        const C_WHITE = 'white';
        const C_GREY = 'grey';
        const C_RED = 'red';
        const C_GREEN = 'green';
        const C_YELLOW = 'yellow';
        const C_BLUE = 'blue';
        const C_MAGENTA = 'magenta';
        const C_CYAN = 'cyan';

        private static $foregroundColors = [
            // Foreground Colors
            'default'=>'39',
            'white' => '37',
            'grey' => '30',
            'red' => '31',
            'green' => '32',
            'yellow' => '33',
            'blue' => '34',
            'magenta' => '35',
            'cyan' => '36',
        ];
        
        private static $backgroundColors = [
            'black' => '40',
            'red' => '41',
            'green' => '42',
            'yellow' => '43',
            'blue' => '44',
            'magenta' => '45',
            'cyan' => '46',
            'grey' => '47',
        ];

        public static function fc($color = 'default', $bold = false){


            $c = self::$foregroundColors[self::$defaultColor];
            if(isset(self::$foregroundColors[$color])){
                $c = self::$foregroundColors[$color];
            }

            $b = $bold?1:0;

            return "\033[".$b.";".$c."m";

        }

        public static function color($text, $color, $bold = false){
            return self::fc($color, $bold).$text.self::fc();
        }
        
        public static function newLine(){
            echo "\n";
        }

        public static function testColors(){
            foreach(self::$foregroundColors as $name=>$code){
                echo self::color('test color ['.$name.'] (regulat)', $name);
                self::newLine();
                echo self::color('test color ['.$name.'] (bold)', $name, true);
                self::newLine();
            }
        }

    }