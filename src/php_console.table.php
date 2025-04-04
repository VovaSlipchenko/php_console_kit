<?php

    namespace PHPConsole;
    
    use PHPConsole\PHPConsoleHelper as C;

    class PHPConsoleTable{

        private $columns;
        private $separator = '|';
        private $separatorHorizontal = '-';
        private $separatorLength = 0;
        private $finalData = [];

        const HIGHTLIGHT_NAGATIVE = 1;
        const HIGHTLIGHT_POSITIVE = 2;
        const HIGHTLIGHT_NEGATIVE_POSITIVE = 3;

        

        function __construct($options){

            if(isset($options['separator'])){
                $this->separator = $options['separator'];
            }

            $spacingLeft = isset($options['spacingLeft'])?$options['spacingLeft']:1;
            $spacingRight = $options['spacingRight']?$options['spacingRight']:1;

            $this->separator = str_repeat(' ', $spacingLeft).$this->separator.str_repeat(' ', $spacingRight);
            $this->separatorLength = strlen($this->separator);

            $this->separator = C::fc(C::C_GREY).$this->separator.C::fc();
            $this->separatorHorizontal = C::fc(C::C_GREY).$this->separatorHorizontal.C::fc();

            foreach($options['columns'] as $column){
                $this->columns[] = Array(
                    'key'=>$column['key'],
                    'title'=>$column['title'],
                    'max_length'=>isset($column['max_length'])?$column['max_length']:50,
                    'proc'=>isset($column['proc'])?$column['proc']:false,
                    'hightlight'=>isset($column['hightlight'])?$column['hightlight']:false,
                );
            }

        }

        function setData($data){

            $this->finalData = Array();

            foreach($this->columns as &$col){
                $col['final_max_length'] = strlen($col['title']);
            }

            foreach($data as $row){
                $finalRow = Array();
                foreach($this->columns as &$col){
                    $value = '';

                    if($col['proc']){
                        $value = $col['proc']($row);
                        //var_dump($value);
                    } else if(isset($row[$col['key']])){
                        $value = $row[$col['key']];
                    }

                    if(strlen($value) > $col['max_length']){
                        $value = substr($value,0,$col['max_length']);
                    }

                    if(strlen($value) > $col['final_max_length']){
                        $col['final_max_length'] = strlen($value);
                    }

                    $finalRow[$col['key']] = $value;
                }
                $this->finalData[] = $finalRow;    
            }
            
        }

        function print(){

            $tableWidth = 0;

            foreach($this->columns as $col){
                $title = str_pad($col['title'], $col['final_max_length']);
                $tableWidth+=strlen($title)+$this->separatorLength;
                $title .= $this->separator;
                echo $title;
            }       

            PHPConsoleHelper::newLine();
            echo str_repeat($this->separatorHorizontal, $tableWidth-1);
            PHPConsoleHelper::newLine();

            foreach($this->finalData as $row){
                foreach($this->columns as $col){
                    $value = $row[$col['key']];
                    $valuePad = str_pad($value, $col['final_max_length']).$this->separator;

                    $color = C::fc();

                    if($col['hightlight']){
                        if(is_callable($col['hightlight'])){
                            $color = $col['hightlight']($row);
                        }
                    }

                    echo C::color($valuePad, $color); 
                }
                PHPConsoleHelper::newLine();
            }

        }

    }