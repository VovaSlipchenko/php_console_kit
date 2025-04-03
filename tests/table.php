<?php

    include dirname(dirname(__FILE__)).'/vendor.php';

    use \PHPConsole\PHPConsoleTable;

    $data = Array(
        Array('first_name'=>'Billy', 'last_name'=>'Jean', 'age'=>18, 'gender'=>'M', 'balance'=>1034),
        Array('first_name'=>'John', 'last_name'=>'Jean', 'age'=>19, 'gender'=>'M', 'balance'=>23034),
        Array('first_name'=>'Sasha', 'last_name'=>'White', 'age'=>22, 'gender'=>'M', 'balance'=>12034),
        Array('first_name'=>'Judith', 'last_name'=>'Mossman', 'age'=>22, 'gender'=>'M', 'balance'=>-666),
    );

    $options = Array(
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
