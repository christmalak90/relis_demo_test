<?php
////////////////////////////////// NEW //////////////////////////////////
/* ReLiS - A Tool for conducting systematic literature reviews and mapping studies.
 * Copyright (C) 2018  Eugene Syriani
 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 
 * --------------------------------------------------------------------------
 *  :Author: Brice Michel Bigendako
 * --------------------------------------------------------------------------
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Unit_test extends CI_Controller
{
    private $user_unitTest;

    function __construct()
    {
        parent::__construct();
        $this->load->helper('test/christ');
        $this->load->helper('test/bane');
        // $this->load->helper('test/project_test');
        // $this->load->helper('test/paper_test');
        // $this->load->helper('test/screening_test');
        // $this->load->helper('test/data_extraction_test');/
        // $this->load->helper('test/quality_assessment_test');
        // $this->load->helper('test/element_test');/
        // $this->load->helper('test/reporting_test');
        $this->load->library('unit_test');
        $this->unit->use_strict(TRUE);
        $this->unit->set_test_items(array('test_controller', 'test_action', 'test_name', 'test_aspect', 'res_value', 'test_value', 'result'));
        $this->user_unitTest = new User_unitTest();
    }

    public function relis_unit_test($result = "html_report")
    {
        $this->user_unitTest->run_tests();
        // project_unitTest();
        // paper_unitTest();
        // screening_unitTest();
        // data_extraction_unitTest();
        // quality_assessment_unitTest();
        // reporting_unitTest();
        // element_unitTest();


        if ($result == "html_report") {
            echo $this->unit->report();
        } elseif ($result == "raw_data") {
            print_r($this->unit->result());
        } elseif ($result == "last_result") {
            print_r($this->unit->last_result());
        }
    }
}