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
    private $response;

    function __construct()
    {
        parent::__construct();
        $this->load->library('unit_test');
        $this->unit->use_strict(TRUE);
        //$this->unit->active(FALSE);
        $this->unit->set_test_items(array('test_controller', 'test_action', 'test_name', 'test_aspect', 'res_value', 'test_value', 'result'));
        //$this->unit->set_test_items(array('test_controller', 'test_action', 'test_name', 'test_aspect', 'res_datatype', 'res_value', 'test_datatype', 'test_value', 'result', 'file', 'line'));
        //$this->unit->set_template($this->report_template());
    }

    public function relis_unit_test($result = "html_report")
    {
        user_unitTest();
        project_unitTest();
        paper_unitTest();
        screening_unitTest();
        data_extraction_unitTest();
        quality_assessment_unitTest();
        reporting_unitTest();
        element_unitTest();


        if ($result == "html_report") {
            echo $this->unit->report();
        } elseif ($result == "raw_data") {
            print_r($this->unit->result());
        } elseif ($result == "last_result") {
            print_r($this->unit->last_result());
        }
    }
}