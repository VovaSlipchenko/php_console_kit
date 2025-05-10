<?php

    namespace PHPConsole;
    
    use PHPConsole\PHPConsoleHelper as C;

    class PHPConsoleTable{

        private $columns;
        private $separatorVertical = '│';
        private $separator = '';
        private $separatorHorizontal = '─';

        private $separatorMiddleLeft = '├';
        private $separatorMiddleRight = '┤';
        private $separatorMiddleMiddle = '┼';

        private $separatorTopLeft = '┌';
        private $separatorTopRight = '┐';
        private $separatorTopMiddle = '┬';

        private $separatorBottomLeft = '└';
        private $separatorBottomRight = '┘';
        private $separatorBottomMiddle = '┴';

        private $separatorLength = 0;
        private $finalData = [];
        private $orderBy = false;
        private $rowNumber = false;
        private $orderDir = 'ASC';
        private $separatorColor = C::C_WHITE;
        private $spacingLeft;
        private $spacingRight;

        private $countMin = false;
        private $countMax = false;
        private $countAvg = false;

        const HIGHTLIGHT_NAGATIVE = 1;
        const HIGHTLIGHT_POSITIVE = 2;
        const HIGHTLIGHT_NEGATIVE_POSITIVE = 3;

        

        function __construct($options){

            if(isset($options['separator'])){
                $this->separator = $options['separator'];
            }

            $this->spacingLeft = isset($options['spacing_left'])?$options['spacing_left']:1;
            $this->spacingRight = isset($options['spacing_right'])?$options['spacing_right']:1;

            $this->rowNumber = isset($options['row_number'])?$options['row_number']:false;
            $this->separator = str_repeat(' ', $this->spacingLeft).$this->separatorVertical.str_repeat(' ', $this->spacingRight);
            $this->separatorWidth = mb_strlen($this->separator, 'UTF-8');

            $this->separator = C::fc(C::C_WHITE).$this->separator.C::fc();
            $this->separatorHorizontal = C::fc(C::C_WHITE).$this->separatorHorizontal.C::fc();

            if($this->rowNumber){
                $this->columns[] = Array(
                    'key'=>'$index',
                    'title'=>'#'
                );
            }

            foreach($options['columns'] as $column){
                $this->columns[] = Array(
                    'key'=>$column['key'],
                    'title'=>$column['title'],
                    'max_length'=>isset($column['max_length'])?$column['max_length']:50,
                    'proc'=>isset($column['proc'])?$column['proc']:false,
                    'hightlight'=>isset($column['hightlight'])?$column['hightlight']:false,
                    'min'=>null,
                    'max'=>null,
                    'avg'=>null,
                );
            }

            $this->orderBy = isset($options['order_by'])?$options['order_by']:false;
            $this->orderDir = isset($options['order_dir'])?$options['order_dir']:false;

        }

        function setData($data){

            $this->finalData = Array();

            foreach($this->columns as &$col){
                $col['final_max_length'] = mb_strlen($col['title'], "UTF-8");
            }

            if($this->orderBy){
                $column = array_column($data, $this->orderBy);
                array_multisort($column, ($this->orderDir == 'DESC')?SORT_DESC:SORT_ASC, $data);
            }

            foreach($data as $index=>$row){
                $finalRow = Array();
                foreach($this->columns as &$col){
                    $value = '';

                    if($col['key'] == '$index'){
                        $value = $index+1;
                    } else {

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
                            $col['final_max_length'] = mb_strlen($value, "UTF-8");
                        }

                    }

                    $finalRow[$col['key']] = $value;
                }
                $this->finalData[] = $finalRow;

            }
            
        }

        function print(){

            $tableWidth = 0;
            $separatorTop = '';
            $separatorMiddle = '';
            $separatorBottom = '';
            $titleRow = '';

            foreach($this->columns as $index=>$col){

                $title = str_pad($col['title'], $col['final_max_length']);
                $titleWidth = mb_strlen($title, "UTF-8");
                
                $sectionWidth = $titleWidth+$this->separatorWidth-1;

                $separatorMiddle.=str_repeat($this->separatorHorizontal, $sectionWidth);
                $separatorTop.=str_repeat($this->separatorHorizontal, $sectionWidth);
                $separatorBottom.=str_repeat($this->separatorHorizontal, $sectionWidth);
                if($index != count($this->columns)-1){
                    $separatorMiddle.=$this->separatorMiddleMiddle;
                    $separatorTop.=$this->separatorTopMiddle;
                    $separatorBottom.=$this->separatorBottomMiddle;
                } else if($index != 0) {
                    $separatorMiddle.=$this->separatorMiddleRight;
                    $separatorTop.=$this->separatorTopRight;
                    $separatorBottom.=$this->separatorBottomRight;
                }

                $title .= $this->separator;
                $titleRow.=$title;

            }       

            $titleRow = $this->separatorVertical.$this->spacingRight.$titleRow;
            $separatorMiddle = $this->separatorMiddleLeft.$separatorMiddle;
            $separatorTop = $this->separatorTopLeft.$separatorTop;
            $separatorBottom = $this->separatorBottomLeft.$separatorBottom;

            echo $separatorTop;
            PHPConsoleHelper::newLine();
            echo $titleRow;
            PHPConsoleHelper::newLine();
            echo $separatorMiddle;
            PHPConsoleHelper::newLine();

            foreach($this->finalData as $row){
                foreach($this->columns as $index=>$col){

                    $value = $row[$col['key']];
                    $valuePad = str_pad($value, $col['final_max_length']).$this->separator;

                    if($index == 0){
                        $valuePad = $this->separatorVertical.str_repeat(' ', $this->spacingRight).$valuePad;
                    }

                    $color = C::fc();

                    if(isset($col['hightlight']) && $col['hightlight']){
                        if(is_callable($col['hightlight'])){
                            $color = $col['hightlight']($row);
                        }
                    }

                    echo C::color($valuePad, $color);

                }
                PHPConsoleHelper::newLine();
            }

            echo $separatorBottom;
            PHPConsoleHelper::newLine();

        }

    }