<?php

    include dirname(dirname(__FILE__)).'/vendor.php';

    use \PHPConsole\PHPConsoleTable;
    use \PHPConsole\PHPConsoleHelper as C;

    $data = Array(
        Array('first_name'=>'Billy', 'last_name'=>'Jean', 'age'=>18, 'gender'=>'M', 'balance'=>1034),
        Array('first_name'=>'John', 'last_name'=>'Jean', 'age'=>19, 'gender'=>'M', 'balance'=>23034),
        Array('first_name'=>'Sasha', 'last_name'=>'White', 'age'=>22, 'gender'=>'F', 'balance'=>12034),
        Array('first_name'=>'Judith', 'last_name'=>'Mossman', 'age'=>22, 'gender'=>'F', 'balance'=>-666),
        Array('first_name'=>'Sam', 'last_name'=>'Bucket', 'age'=>26, 'gender'=>'M', 'balance'=>1656),
        Array('first_name'=>'Baney', 'last_name'=>'Hoffman', 'age'=>22, 'gender'=>'M', 'balance'=>-120),
        Array('first_name'=>'Samanta', 'last_name'=>'Smith', 'age'=>32, 'gender'=>'F', 'balance'=>6453),
    );

    $options = Array(
        'order_by'=>'balance',
        'order_dir'=>'DESC',
        'row_number'=>true,
        'columns'=>Array(
            Array(
                'title'=>'Name', 
                'key'=>'name',
                'proc'=> function($row){ return $row['first_name'].' '.$row['last_name']; }
            ),
            Array(
                'title'=>'Age',
                'key'=>'age',
            ),
            Array(
                'title'=>'Gen',
                'key'=>'gender',
                'hightlight'=>function($row){ 
                    if($row['gender'] == 'M') return C::C_BLUE; 
                    if($row['gender'] == 'F') return C::C_MAGENTA;  
                }
            ),
            Array(
                'title'=>'Balance',
                'key'=>'balance',
            )

        )
    );

    $table = new PHPConsoleTable($options);
    $table->setData($data);
    $table->print();
