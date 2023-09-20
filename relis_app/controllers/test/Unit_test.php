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
        $this->unit->set_test_items(array('test_name', 'tested_aspect', 'res_datatype', 'res_value', 'test_datatype', 'test_value', 'result', 'file', 'line'));
        //$this->unit->set_template($this->report_template());
    }

    public function relis_unit_test($result = "html_report")
    {
        #################### TEST USER CONTROLLER ####################

        //--------------------- new_user action --------------------//

        /*
         * Controller : User
         * Action : new_user
         * Description : This test verifies the behavior of the 'new_user' action when a user is not already logged in (there is no open user session).
         * Scenario : When a user is not logged in and clicks the 'create user' link, the user should be directed to the 'new_user' HTML form to create a new user.
         * Expected HTTP Response Code : 200 (indicating a successful response from the server).
         * Expected URL : user/new_user (The expected URL after the user's action)
         */
        $this->session->set_userdata('user_id', 0);
        $test_name = "Go to new user form page when user is not already logged in";
        $test_aspect_HTTP_code = "HTTP response code";
        $test_aspect_url = "Target URL";
        $get_response = http_GET('user/new_user');

        $expected_response_code = 200 . ' ' . stautus_code_description()[200];
        $expected_url = 'user/new_user';

        $response_code = $get_response['status_code'];
        preg_match('/8083\/(.*?)(\.html)?$/', $get_response['url'], $matches);
        $url = $matches[1];

        $this->unit->run($response_code, $expected_response_code, $test_name, $test_aspect_HTTP_code);
        $this->unit->run($url, $expected_url, $test_name, $test_aspect_url);

        /*
         * Controller : User
         * Action : new_user
         * Description : This test verifies the behavior of the 'new_user' action when a user is already logged in (there is an open user session).
         * Scenario : When a user is already logged in and clicks the 'create user' link, the user should be redirected to the 'home/index' url to be automatically logged in.
         * Expected HTTP Response Code : 307 (indicating a temporary Redirect code)
         * Expected URL : home (The expected URL after the user's action)
         */
        $this->session->set_userdata('user_id', 1);
        $test_name = "Go to new user form page when user is already logged in";
        $test_aspect_HTTP_code = "HTTP response code";
        $test_aspect_url = "Target URL";
        $get_response = http_GET('user/new_user', ['Cookie: relis_session=dsqrv2lu49khh5g6gmvsq8pked3vabjt']);

        $expected_response_code = 307 . ' ' . stautus_code_description()[307];
        $expected_url = 'home';

        $response_code = $get_response['status_code'];
        preg_match('/8083\/(.*?)(\.html)?$/', $get_response['url'], $matches);
        $url = $matches[1];

        $this->unit->run($response_code, $expected_response_code, $test_name, $test_aspect_HTTP_code);
        $this->unit->run($url, $expected_url, $test_name, $test_aspect_url);


        //--------------------- check_create_user action --------------------//

        /*
         * Controller : User
         * Action : check_create_user
         * Description : Submit new user form while all the form fields are empty.
         * Scenario : When a user click the create user button, they should receive a message saying "The Name field is required, The Email field is required, The Username field is required, The Password field is required, The Validate password field is required"
         * Expected HTTP Response Code : 200
         * Expected URL : user/check_create_user (The expected URL after the form submission)
         */
        $this->session->set_userdata('user_id', 0);
        $test_name = "Submit new user form while all the form fields are empty";
        $test_aspect_HTTP_code = "HTTP response code";
        $test_aspect_url = "Target URL";
        $test_aspect_warning_message = "Warning message";
        $get_response = http_POST('user/check_create_user', ['user_name' => '', 'user_mail' => '', 'user_username' => '', 'user_password' => '', 'user_password_validate' => '']);

        $expected_response_code = 200 . ' ' . stautus_code_description()[200];
        $expected_url = 'user/check_create_user';
        $expected_message = [
            'The Name field is required',
            'The Email field is required',
            'The Username field is required',
            'The Password field is required',
            'The Validate password field is required'
        ];

        $response_code = $get_response['status_code'];
        preg_match('/8083\/(.*?)(\.html)?$/', $get_response['url'], $matches);
        $url = $matches[1];
        $message = [];
        foreach ($expected_message as $msg) {
            if (strpos($get_response['content'], $msg) !== false) {
                array_push($message, $msg);
            }
        }
        $expected_message = implode(", ", $expected_message);
        $message = implode(", ", $message);

        $this->unit->run($response_code, $expected_response_code, $test_name, $test_aspect_HTTP_code);
        $this->unit->run($url, $expected_url, $test_name, $test_aspect_url);
        $this->unit->run($message, $expected_message, $test_name, $test_aspect_warning_message);

        /*
         * Controller : User
         * Action : check_create_user
         * Description : Submit new user form while all the form fields are correctly filed but the email field is not valid.
         * Scenario : When a user click the create user button, they should receive a message saying "The Email field must contain a valid email address"
         * Expected HTTP Response Code : 200
         * Expected URL : user/check_create_user (The expected URL after the form submission)
         */
        $this->session->set_userdata('user_id', 0);
        $test_name = "Submit new user form while all the form fields are correctly filed but the email field is not valid";
        $test_aspect_HTTP_code = "HTTP response code";
        $test_aspect_url = "Target URL";
        $test_aspect_warning_message = "Warning message";
        $get_response = http_POST('user/check_create_user', ['user_name' => 'christian', 'user_mail' => '123gmai.com', 'user_username' => 'Malakani', 'user_password' => '123', 'user_password_validate' => '123']);

        $expected_response_code = 200 . ' ' . stautus_code_description()[200];
        $expected_url = 'user/check_create_user';
        $expected_message = "The Email field must contain a valid email address";

        $response_code = $get_response['status_code'];
        preg_match('/8083\/(.*?)(\.html)?$/', $get_response['url'], $matches);
        $url = $matches[1];
        $message = "";
        if (strpos($get_response['content'], $expected_message) !== false) {
            $message = $expected_message;
        }

        $this->unit->run($response_code, $expected_response_code, $test_name, $test_aspect_HTTP_code);
        $this->unit->run($url, $expected_url, $test_name, $test_aspect_url);
        $this->unit->run($message, $expected_message, $test_name, $test_aspect_warning_message);

        /*
         * Controller : User
         * Action : check_create_user
         * Description : Submit new user form while all the form fields are correctly filed but the validate password field doesn't match the password field.
         * Scenario : When a user click the create user button, they should receive a message saying "The Password field does not match the Validate password field"
         * Expected HTTP Response Code : 200
         * Expected URL : user/check_create_user (The expected URL after the form submission)
         */
        $this->session->set_userdata('user_id', 0);
        $test_name = "Submit new user form while all the form fields are correctly filed but the validate password field doesn't match the password field";
        $test_aspect_HTTP_code = "HTTP response code";
        $test_aspect_url = "Target URL";
        $test_aspect_warning_message = "Warning message";
        $get_response = http_POST('user/check_create_user', ['user_name' => 'christian', 'user_mail' => '123@gmai.com', 'user_username' => 'Malakani', 'user_password' => '123', 'user_password_validate' => '12']);

        $expected_response_code = 200 . ' ' . stautus_code_description()[200];
        $expected_url = 'user/check_create_user';
        $expected_message = "The Password field does not match the Validate password field";

        $response_code = $get_response['status_code'];
        preg_match('/8083\/(.*?)(\.html)?$/', $get_response['url'], $matches);
        $url = $matches[1];
        $message = "";
        if (strpos($get_response['content'], $expected_message) !== false) {
            $message = $expected_message;
        }

        $this->unit->run($response_code, $expected_response_code, $test_name, $test_aspect_HTTP_code);
        $this->unit->run($url, $expected_url, $test_name, $test_aspect_url);
        $this->unit->run($message, $expected_message, $test_name, $test_aspect_warning_message);

        /*
         * Controller : User
         * Action : check_create_user
         * Description : Submit new user form while all the form fields are correctly filed but the reCAPTCHA is not checked.
         * Scenario : When a user click the create user button, they should receive a message saying "Sorry Recaptcha Unsuccessful"
         * Expected HTTP Response Code : 200
         * Expected URL : user/check_create_user (The expected URL after the form submission)
         */
        $this->session->set_userdata('user_id', 0);
        $test_name = "Submit new user form while all the form fields are correctly filed but the reCAPTCHA is not checked";
        $test_aspect_HTTP_code = "HTTP response code";
        $test_aspect_url = "Target URL";
        $test_aspect_warning_message = "Warning message";
        $get_response = http_POST('user/check_create_user', ['user_name' => 'christian', 'user_mail' => '123@gmai.com', 'user_username' => 'Malakani', 'user_password' => '123', 'user_password_validate' => '123']);

        $expected_response_code = 200 . ' ' . stautus_code_description()[200];
        $expected_url = 'user/check_create_user';
        $expected_message = "Sorry Recaptcha Unsuccessful";

        $response_code = $get_response['status_code'];
        preg_match('/8083\/(.*?)(\.html)?$/', $get_response['url'], $matches);
        $url = $matches[1];
        $message = "";
        if (strpos($get_response['content'], $expected_message) !== false) {
            $message = $expected_message;
        }

        $this->unit->run($response_code, $expected_response_code, $test_name, $test_aspect_HTTP_code);
        $this->unit->run($url, $expected_url, $test_name, $test_aspect_url);
        $this->unit->run($message, $expected_message, $test_name, $test_aspect_warning_message);

        /*
         * Controller : User
         * Action : check_create_user
         * Description : Submit new user form while all the form fields are correctly filed, the reCAPTCHA is checked, but the Username is already used.
         * Scenario : When a user click the create user button, they should receive a message saying "Username already used"
         * Expected HTTP Response Code : 200
         * Expected URL : user/check_create_user (The expected URL after the form submission)
         */
        $this->session->set_userdata('user_id', 0);
        $test_name = "Submit new user form while all the form fields are correctly filed, the reCAPTCHA is checked, but the Username is already used";
        $test_aspect_HTTP_code = "HTTP response code";
        $test_aspect_url = "Target URL";
        $test_aspect_warning_message = "Warning message";
        $get_response = http_POST('user/check_create_user', ['user_name' => 'chris', 'user_mail' => '123@gmai.com', 'user_username' => 'admin', 'user_password' => '123', 'user_password_validate' => '123', 'g-recaptcha-response' => 'relis_test']);

        $expected_response_code = 200 . ' ' . stautus_code_description()[200];
        $expected_url = 'user/check_create_user';
        $expected_message = "Username already used";

        $response_code = $get_response['status_code'];
        preg_match('/8083\/(.*?)(\.html)?$/', $get_response['url'], $matches);
        $url = $matches[1];
        $message = "";
        if (strpos($get_response['content'], $expected_message) !== false) {
            $message = $expected_message;
        }

        $this->unit->run($response_code, $expected_response_code, $test_name, $test_aspect_HTTP_code);
        $this->unit->run($url, $expected_url, $test_name, $test_aspect_url);
        $this->unit->run($message, $expected_message, $test_name, $test_aspect_warning_message);

        /*
         * Controller : User
         * Action : check_create_user
         * Description : Submit new user form while all the form fields are correctly filed and the reCAPTCHA is checked.
         * Scenario : When a user click the create user button, a new user is created in the database and the user is redirected to the validate email page"
         * Expected HTTP Response Code : 200
         * Expected URL : user/check_create_user (The expected URL after the form submission)
         */
        $this->session->set_userdata('user_id', 0);
        $test_name = "Submit new user form while all the form fields are correctly filed and the reCAPTCHA is checked";
        $test_aspect_HTTP_code = "HTTP response code";
        $test_aspect_url = "Target URL";
        $test_aspect_success_message = "Success message";
        $test_aspect_created_user = "Created user";
        $test_aspect_created_user_creation = "User creation";

        $user_name = "christian";
        $user_mail = "123@gmai.com";
        $user_username = "Malakani";
        $user_usergroup = "2";
        $created_by = "1";
        $user_state = "0";
        $user_active = "1";
        $get_response = http_POST('user/check_create_user', ['user_name' => $user_name, 'user_mail' => $user_mail, 'user_username' => $user_username, 'user_password' => '123', 'user_password_validate' => '123', 'g-recaptcha-response' => 'relis_test']);
        $sql_user = "Select user_id, user_name, user_mail, user_username, user_usergroup, created_by, user_state, user_active from users WHERE user_username = '" . $user_username . "'";
        $user_data = $this->db->query($sql_user)->row_array();
        $sql_user_creation = "Select creation_user_id, confirmation_code, confirmation_try, user_creation_active from user_creation WHERE creation_user_id = " . $user_data['user_id'] . "";
        $user_creation_data = $this->db->query($sql_user_creation)->row_array();

        $expected_response_code = 200 . ' ' . stautus_code_description()[200];
        $expected_url = 'user/check_create_user';
        $expected_message = "A validation code has been sent to your email";
        $expected_created_user = json_encode($user_data);
        $expected_user_creation = json_encode($user_creation_data);

        $response_code = $get_response['status_code'];
        preg_match('/8083\/(.*?)(\.html)?$/', $get_response['url'], $matches);
        $url = $matches[1];
        $message = "";
        if (strpos($get_response['content'], $expected_message) !== false) {
            $message = $expected_message;
        }
        $created_user = array(
            "user_id" => $user_data['user_id'],
            "user_name" => $user_name,
            "user_mail" => $user_mail,
            "user_username" => $user_username,
            "user_usergroup" => $user_usergroup,
            "created_by" => $created_by,
            "user_state" => $user_state,
            "user_active" => $user_active
        );
        $created_user = json_encode($created_user);

        $user_creation = array(
            "creation_user_id" => $user_data['user_id'],
            "confirmation_code" => $user_creation_data['confirmation_code'],
            "confirmation_try" => "0",
            "user_creation_active" => "1"
        );
        $user_creation = json_encode($user_creation);

        $this->unit->run($response_code, $expected_response_code, $test_name, $test_aspect_HTTP_code);
        $this->unit->run($url, $expected_url, $test_name, $test_aspect_url);
        $this->unit->run($message, $expected_message, $test_name, $test_aspect_success_message);
        $this->unit->run($created_user, $expected_created_user, $test_name, $test_aspect_created_user);
        $this->unit->run($user_creation, $expected_user_creation, $test_name, $test_aspect_created_user_creation);


        //--------------------- validate_user action --------------------//

        /*
         * Controller : User
         * Action : validate_user
         * Description : validate_user action displays the Form for account validation after signing up.
         * Expected HTTP Response Code : 200 (indicating a successful response from the server).
         * Expected URL : user/validate_user (The expected URL after the user's action)
         */
        $test_name = "Check the displaying of the Form for account validation after signing up";
        $test_aspect_HTTP_code = "HTTP response code";
        $test_aspect_url = "Target URL";
        $get_response = http_GET('user/validate_user');

        $expected_response_code = 200 . ' ' . stautus_code_description()[200];
        $expected_url = 'user/validate_user';

        $response_code = $get_response['status_code'];
        preg_match('/8083\/(.*?)(\.html)?$/', $get_response['url'], $matches);
        $url = $matches[1];

        $this->unit->run($response_code, $expected_response_code, $test_name, $test_aspect_HTTP_code);
        $this->unit->run($url, $expected_url, $test_name, $test_aspect_url);


        //--------------------- check_validation action --------------------//

        /*
         * Controller : User
         * Action : check_validation
         * Description : Submit the account validation form with an empty validation_code field.
         * Scenario : When a user submit the account validation form with an empty validation_code field, they should receive a message saying "Validation Error please provide validation code"
         * Expected HTTP Response Code : 200 (indicating a successful response from the server).
         * Expected URL : user/validate_user (The expected URL after the user's action)
         */
        $test_name = "Submit the account validation form with an empty validation_code field";
        $test_aspect_HTTP_code = "HTTP response code";
        $test_aspect_url = "Target URL";
        $test_aspect_warning_message = "Warning message";
        $get_response = http_POST('user/check_validation', ['user_id' => $user_data['user_id'], 'validation_code' => '']);

        $expected_response_code = 200 . ' ' . stautus_code_description()[200];
        $expected_url = 'user/check_validation';
        $expected_message = 'Validation Error please provide validation code';

        $response_code = $get_response['status_code'];
        preg_match('/8083\/(.*?)(\.html)?$/', $get_response['url'], $matches);
        $url = $matches[1];
        $message = '';
        if (strpos($get_response['content'], $expected_message) !== false) {
            $message = $expected_message;
        }

        $this->unit->run($response_code, $expected_response_code, $test_name, $test_aspect_HTTP_code);
        $this->unit->run($url, $expected_url, $test_name, $test_aspect_url);
        $this->unit->run($message, $expected_message, $test_name, $test_aspect_warning_message);

        /*
         * Controller : User
         * Action : check_validation
         * Description : Submit the account validation form with a wrong validation code.
         * Scenario : When a user submit the account validation form with a wrong validation_code, they should receive a message saying "Wrong validation code"
         * Expected HTTP Response Code : 200 (indicating a successful response from the server).
         * Expected URL : user/validate_user (The expected URL after the user's action)
         */
        $test_name = "Submit the account validation form with a wrong validation code";
        $test_aspect_HTTP_code = "HTTP response code";
        $test_aspect_url = "Target URL";
        $test_aspect_warning_message = "Warning message";
        $get_response = http_POST('user/check_validation', ['user_id' => $user_data['user_id'], 'validation_code' => '1234']);

        $expected_response_code = 200 . ' ' . stautus_code_description()[200];
        $expected_url = 'user/check_validation';
        $expected_message = 'Wrong validation code';

        $response_code = $get_response['status_code'];
        preg_match('/8083\/(.*?)(\.html)?$/', $get_response['url'], $matches);
        $url = $matches[1];
        $message = '';
        if (strpos($get_response['content'], $expected_message) !== false) {
            $message = $expected_message;
        }

        $this->unit->run($response_code, $expected_response_code, $test_name, $test_aspect_HTTP_code);
        $this->unit->run($url, $expected_url, $test_name, $test_aspect_url);
        $this->unit->run($message, $expected_message, $test_name, $test_aspect_warning_message);

        /*
         * Controller : User
         * Action : check_validation
         * Description : Submit the account validation form with a correct validation code.
         * Scenario : When a user submit the account validation form with a correct validation_code, they should receive a message saying "Accound validated you can now user ReLiS"
         * Expected HTTP Response Code : 200 (indicating a successful response from the server).
         * Expected URL : user/validate_user (The expected URL after the user's action)
         */
        $test_name = "Submit the account validation form with a correct validation code";
        $test_aspect_HTTP_code = "HTTP response code";
        $test_aspect_url = "Target URL";
        $test_aspect_message = "Success message";
        $test_aspect_updated_user = "Updated user";
        $test_aspect_updated_user_creation = "Updated user creation";

        $get_response = http_POST('user/check_validation', ['user_id' => $user_data['user_id'], 'validation_code' => $user_creation_data['confirmation_code']]);
        $sql_user = "Select user_id, user_name, user_mail, user_username, user_usergroup, created_by, user_state, user_active from users WHERE user_id = '" . $user_data['user_id'] . "'";
        $user_data = $this->db->query($sql_user)->row_array();
        $sql_user_creation = "Select creation_user_id, confirmation_try, user_creation_active from user_creation WHERE creation_user_id = " . $user_data['user_id'] . "";
        $user_creation_data = $this->db->query($sql_user_creation)->row_array();

        $expected_response_code = 200 . ' ' . stautus_code_description()[200];
        $expected_url = 'user/check_validation';
        $expected_message = 'Accound validated you can now user ReLiS';
        $expected_updated_user_data = json_encode($user_data);
        $expected_updated_user_creation_data = json_encode($user_creation_data);

        $response_code = $get_response['status_code'];
        preg_match('/8083\/(.*?)(\.html)?$/', $get_response['url'], $matches);
        $url = $matches[1];
        $message = '';
        if (strpos($get_response['content'], $expected_message) !== false) {
            $message = $expected_message;
        }
        $updated_user_data = array(
            "user_id" => $user_data['user_id'],
            "user_name" => $user_name,
            "user_mail" => $user_mail,
            "user_username" => $user_username,
            "user_usergroup" => $user_usergroup,
            "created_by" => $created_by,
            "user_state" => "1",
            "user_active" => $user_active
        );
        $updated_user_data = json_encode($updated_user_data);

        $updated_user_creation = array(
            "creation_user_id" => $user_data['user_id'],
            "confirmation_try" => "1",
            "user_creation_active" => "0"
        );
        $updated_user_creation = json_encode($updated_user_creation);

        $this->unit->run($response_code, $expected_response_code, $test_name, $test_aspect_HTTP_code);
        $this->unit->run($url, $expected_url, $test_name, $test_aspect_url);
        $this->unit->run($message, $expected_message, $test_name, $test_aspect_message);
        $this->unit->run($updated_user_data, $expected_updated_user_data, $test_name, $test_aspect_updated_user);
        $this->unit->run($updated_user_creation, $expected_updated_user_creation_data, $test_name, $test_aspect_updated_user_creation);


        //--------------------- login action --------------------//

        
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