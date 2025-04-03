<?php

    namespace PHPConsole;
    
    use PHPConsole\PHPConsoleHelper;

    class PHPConsoleTable{

        private $columns;
        private $separator = ' | ';
        private $finalData = [];

        function __construct($options){

            foreach($options['columns'] as $column){
                $this->columns[] = Array(
                    'key'=>$column['key'],
                    'title'=>$column['title'],
                    'max_length'=>isset($column['max_length'])?$column['max_length']:50,
                    'proc'=>isset($column['proc'])?$column['proc']:false
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
                $title = str_pad($col['title'], $col['final_max_length']).$this->separator;
                $tableWidth+=strlen($title);
                echo $title;
            }       
            PHPConsoleHelper::newLine();
            echo str_repeat('-', $tableWidth-1);
            PHPConsoleHelper::newLine();

            foreach($this->finalData as $row){
                foreach($this->columns as $col){
                    $value = $row[$col['key']];
                    $valuePad = str_pad($value, $col['final_max_length']).$this->separator;
                    echo $valuePad;    
                }
                PHPConsoleHelper::newLine();
            }

        }

    }