<?php
/////////////////////////////////////// NEW ///////////////////////////////////

function run_test($controller, $action, $test_name, $test_aspect, $expected_values, $actual_values)
{
    $ci = get_instance();
    $ci->unit->run($actual_values, $expected_values, $test_name, $test_aspect, $controller, $action);
}