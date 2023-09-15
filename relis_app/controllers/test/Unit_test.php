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
        //$this->unit->set_test_items(array('test_name', 'result'));
        //$this->unit->set_template($this->report_template());
    }

    // private function report_template()
    // {
    //     return '
    //     <table border="0" cellpadding="4" cellspacing="1">
    //     {rows}
    //             <tr>
    //                     <td>{item}</td>
    //                     <td>{result}</td>
    //             </tr>
    //     {/rows}
    //     </table>';
    // }

    public function relis_unit_test($result = "html_report")
    {
        #################### TEST USER CONTROLLER ####################

        //--------------------- new_user action --------------------//

        /*
         * Controller : User
         * Action : new_user
         * Description : This test verifies the behavior of the 'new_user' action when a user is not already logged in (there is no open user session).
         * Scenario : When a user is not logged in and clicks the 'create user' link, the user should be directed to the 'new_user' HTML page to create a new user.
         * Expected HTTP Response Code : 200 (indicating a successful response from the server).
         * Expected URL : user/new_user (The expected URL after the user's action)
         */
        $test_name = "Test new_user action when user is not already logged in";
        $this->session->set_userdata('user_id', 0);
        $get_response = http_GET('user/new_user');
        $response_code = $get_response['status_code'];
        preg_match('/8083\/(.*?)(\.html)?$/', $get_response['url'], $matches);
        $url = $matches[1];
        $expected_response_code = 200;
        $expected_url = 'user/new_user';
        $this->unit->run($response_code, $expected_response_code, $test_name);
        $this->unit->run($url, $expected_url, $test_name);

        /*
         * Controller : User
         * Action : new_user
         * Description : This test verifies the behavior of the 'new_user' action when a user is already logged in (there is an open user session).
         * Scenario : When a user is already logged in and clicks the 'create user' link, the user should be redirected to the 'home/index' url to be automatically logged in.
         * Expected HTTP Response Code : 307 (indicating a temporary Redirect code)
         * Expected URL : home (The expected URL after the user's action)
         */
        $test_name = "Test new_user action when user is already logged in";
        $this->session->set_userdata('user_id', 1);
        $get_response = http_GET('user/new_user', ['Cookie: relis_session=dsqrv2lu49khh5g6gmvsq8pked3vabjt']);
        $response_code = $get_response['status_code'];
        preg_match('/8083\/(.*?)(\.html)?$/', $get_response['url'], $matches);
        $url = $matches[1];
        $expected_response_code = 307;
        $expected_url = 'home';
        $this->unit->run($response_code, $expected_response_code, $test_name);
        $this->unit->run($url, $expected_url, $test_name);


        #################### TEST PROJECT CONTROLLER ####################

        #################### TEST PAPER CONTROLLER ####################

        #################### TEST SCREENING CONTROLLER ####################

        #################### TEST DATA EXTRACTION CONTROLLER ####################

        #################### TEST QUALITY ASSESSMENT CONTROLLER ####################

        #################### TEST REPORTING CONTROLLER ####################

        #################### TEST ELEMENT CONTROLLER ####################


        #################### REPORT ####################

        if ($result == "html_report") {
            echo $this->unit->report();
        } elseif ($result == "raw_data") {
            print_r($this->unit->result());
        } elseif ($result == "last_result") {
            print_r($this->unit->last_result());
        }
    }
}


//echo "userData: " . $this->session->userdata('user_id'); ////////////////////////////////////////////////
//echo "<br> url: " . $url; ////////////////////////////////////////////////////////////////////////////////
//echo "<br> code: " . $response_code; /////////////////////////////////////////////////////////////////////
//print_r($get_response['headers']);/////////////////////////////////////////////////////////////////////
//echo "<br> content:" .$get_response['content'];///////////////////////////////////////////////////////
//$get_response = http_POST('user/check_form', ['user_username' => 'admin', 'user_password' => '123']);
