<?php

    include dirname(dirname(__FILE__)).'/vendor.php';

    use \PHPConsole\PHPConsoleHelper as C;

    //C::testColors();
    C::print('<red>test</red> <green>colorful</green> <blue>string</blue>');

    C::setTimeMark();
    sleep(5);
    C::getTimeMark();
    C::getTimeMarkMs();
