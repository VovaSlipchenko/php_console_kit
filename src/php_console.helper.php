<?php

    namespace PHPConsole;

    class PHPConsoleHelper{

        private static $time_marks = Array();

        public static $defaultColor = 'white';
        public static $defaultBgColor = 'white';

        public static $allowEverywhere = false;

        const DEFAULT_TIMEMARK_NAME = 'process';

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

        public static function e($text){ 
            if (php_sapi_name() == "cli" || self::$allowEverywhere) {
                echo $text;
            } else {
                // Not in cli
            }
        }

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
            self::e("\n");
        }

        public static function getStr($text){
            foreach(self::$foregroundColors as $name=>$color){
                $text = str_replace('<'.$name.'>', self::fc($name), $text);
                $text = str_replace('</'.$name.'>', self::fc(self::C_DEFAULT), $text);
            }
            return $text;
        }

        public static function print($text, $color = false){
            if($color){
                self::e(self::fc($color));
            }
            self::e(self::getStr($text));
            self::e(self::fc(self::C_DEFAULT));
            self::e(self::newLine());
        }

        public static function simple($text, $level = 0){
            self::log("", "", $text, $level);
        }

        public static function inactive($text, $level = 0){
            self::log('gray', "info", $text, $level);
        }

        public static function success($text, $level = 0){
            self::log('green', "success", $text, $level);
        }

        public static function error($text, $level = 0){
            self::log('red', "error  ", $text, $level);
        }

        public static function warning($text, $level = 0){
            self::log('yellow', "error  ", $text, $level);
        }

        public static function info($text, $level = 0){
            self::log('blue', "info   ", $text, $level);
        }

        public static function printTime(){
            $format = 'H:i:s';
            self::e("[".date('H:i:s')."] ");
        }

        public static function log($color, $prefix = '', $text, $level = 0){

            $str = str_repeat("  ", $level);

            if($prefix){
                $str .= "[".self::color($prefix, $color)."] ";
                //$str .= "[".$color.$prefix."\e[39m]";
            }

            self::printTime(false);

            $str .= $text;
            $str .= "\r\n";

            self::e($str);

        }

        public static function setTimeMark($name = self::DEFAULT_TIMEMARK_NAME){
            self::$time_marks[$name] = microtime(true);
        }

        public static function getTimeMark($name = self::DEFAULT_TIMEMARK_NAME, $millisecondsMode = false){
            if(isset(self::$time_marks[$name])){
                $milliseconds = round(microtime(true) - self::$time_marks[$name])*1000;
                $seconds = $milliseconds / 1000;
                if($millisecondsMode){
                    $output = $milliseconds." ms";
                } else {
                    $output = sprintf('%02d:%02d:%02d', intval($seconds/ 3600),intval($seconds/ 60 % 60), intval($seconds% 60));
                }
                self::print($name." finished in ".$output);
            } else {
                self::error("Time mark '".$name."' is not set");
            }
        }

        public static function getTimeMarkMs($name = self::DEFAULT_TIMEMARK_NAME){
            self::getTimeMark($name, true);
        }

        public static function progress($total, $current){

            $perc = ($current / $total) * 100;            
            self::e("\r");
            self::e("                                              ");
            self::e("\r");
            self::e(str_pad(round($perc, 1),5, ' ', STR_PAD_LEFT)."% (".$current."/".$total.")");

        }

        public static function testColors(){
            foreach(self::$foregroundColors as $name=>$code){
                self::e(self::color('test color ['.$name.'] (regulat)', $name));
                self::newLine();
                self::e(self::color('test color ['.$name.'] (bold)', $name, true));
                self::newLine();
            }
        }

    }