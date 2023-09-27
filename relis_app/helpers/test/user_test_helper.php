<?php
/////////////////////////////////////// NEW ///////////////////////////////////

// TEST USER CONTROLLER
function user_unitTest()
{
    $ci = get_instance();
    $controller = "User";

    //--------------------- index action --------------------//

    $action = "index";

    /*
     * Controller : User
     * Action : index
     * Description : This test verifies the behavior of the 'index' action.
     * Scenario : When a user navigate to "user/index" url they should be taken to the ReLiS description page.
     * Expected HTTP Response Code : 200 (indicating a successful response from the server).
     */
    $test_name = "Go to ReLiS description page";
    $test_aspects = ["HTTP response code"];

    $get_response = http_GET('user/index');

    $expected_values[$test_aspects[0]] = 200 . " " . status_code_description()[200];
    $actual_values[$test_aspects[0]] = $get_response['status_code'];

    test_unit($controller, $action, $test_name, $test_aspects, $expected_values, $actual_values);


    //--------------------- new_user action --------------------//

    $action = "new_user";

    /*
     * Controller : User
     * Action : new_user
     * Description : This test verifies the behavior of the 'new_user' action when a user is not already logged in (there is no open user session).
     * Scenario : When a user is not logged in and clicks the 'create user' link, the user should be directed to the 'new_user' HTML form to create a new user.
     * Expected HTTP Response Code : 200 (indicating a successful response from the server).
     */
    $test_name = "Go to new user form page when user is not already logged in";
    $test_aspects = ["HTTP response code"];

    $get_response = http_GET('user/new_user');

    $expected_values[$test_aspects[0]] = 200 . " " . status_code_description()[200];
    $actual_values[$test_aspects[0]] = $get_response['status_code'];

    test_unit($controller, $action, $test_name, $test_aspects, $expected_values, $actual_values);

    /*
     * Controller : User
     * Action : new_user
     * Description : This test verifies the behavior of the 'new_user' action when a user is already logged in (there is an open user session).
     * Scenario : When a user is already logged in and clicks the 'create user' link, the user should be redirected to the 'home/index' url to be automatically logged in.
     * Expected HTTP Response Code : 307 (indicating a temporary Redirect code)
     * Expected URL : home (The expected URL after the user's action)
     */
    $test_name = "Go to new user form page when user is already logged in";
    $test_aspects = ["HTTP response code", "Target URL"];

    $expected_values[$test_aspects[0]] = 307 . " " . status_code_description()[307];
    $expected_values[$test_aspects[1]] = 'home';

    $get_response = http_GET('user/new_user', ['Cookie: relis_session=dsqrv2lu49khh5g6gmvsq8pked3vabjt']);

    $actual_values[$test_aspects[0]] = $get_response['status_code'];
    preg_match('/8083\/(.*?)(\.html)?$/', $get_response['url'], $matches);
    $url = $matches[1];
    $actual_values[$test_aspects[1]] = $url;

    test_unit($controller, $action, $test_name, $test_aspects, $expected_values, $actual_values);


    //--------------------- check_create_user action --------------------//

    $action = "check_create_user";

    /*
     * Controller : User
     * Action : check_create_user
     * Description : Submit new user form while all the form fields are empty.
     * Scenario : When the user submit the new user form, No data shoud be inserted into the users table
     * Expected HTTP Response Code : 200
     * Expected users table last ID: the users table last user ID should be the same before and after the test

     */
    $test_name = "Submit new user form while all the form fields are empty";
    $test_aspects = ["HTTP response code", "users table last ID"];

    $expected_values[$test_aspects[0]] = 200 . " " . status_code_description()[200];
    $expected_values[$test_aspects[1]] = $ci->db->query("SELECT user_id FROM users ORDER BY user_id DESC LIMIT 1")->row_array()['user_id'];

    $get_response = http_POST('user/check_create_user', ['user_name' => '', 'user_mail' => '', 'user_username' => '', 'user_password' => '', 'user_password_validate' => '']);

    $actual_values[$test_aspects[0]] = $get_response['status_code'];
    $actual_values[$test_aspects[1]] = $ci->db->query("SELECT user_id FROM users ORDER BY user_id DESC LIMIT 1")->row_array()['user_id'];

    test_unit($controller, $action, $test_name, $test_aspects, $expected_values, $actual_values);

    /*
     * Controller : User
     * Action : check_create_user
     * Description : Submit new user form while all the form fields are correctly filed but the email field is not valid.
     * Scenario : When the user submit the new user form, No data shoud be inserted into the users table
     * Expected HTTP Response Code : 200
     * Expected users table last ID: the users table last user ID should be the same before and after the testyy
     */
    $test_name = "Submit new user form while all the form fields are correctly filed but the email field is not valid";
    $test_aspects = ["HTTP response code", "users table last ID"];

    $expected_values[$test_aspects[0]] = 200 . " " . status_code_description()[200];
    $expected_values[$test_aspects[1]] = $ci->db->query("SELECT user_id FROM users ORDER BY user_id DESC LIMIT 1")->row_array()['user_id'];

    $get_response = http_POST('user/check_create_user', ['user_name' => 'christian', 'user_mail' => '123gmai.com', 'user_username' => 'Malakani', 'user_password' => '123', 'user_password_validate' => '123']);

    $actual_values[$test_aspects[0]] = $get_response['status_code'];
    $actual_values[$test_aspects[1]] = $ci->db->query("SELECT user_id FROM users ORDER BY user_id DESC LIMIT 1")->row_array()['user_id'];

    test_unit($controller, $action, $test_name, $test_aspects, $expected_values, $actual_values);

    /*
     * Controller : User
     * Action : check_create_user
     * Description : Submit new user form while all the form fields are correctly filed but the validate password field doesn't match the password field.
     * Scenario : When the user submit the new user form, No data shoud be inserted into the users table
     * Expected HTTP Response Code : 200
     * Expected users table last id: the users table last user ID should be the same before and after the test
     */
    $test_name = "Submit new user form while all the form fields are correctly filed but the validate password field doesn't match the password field";
    $test_aspects = ["HTTP response code", "users table last ID"];

    $expected_values[$test_aspects[0]] = 200 . " " . status_code_description()[200];
    $expected_values[$test_aspects[1]] = $ci->db->query("SELECT user_id FROM users ORDER BY user_id DESC LIMIT 1")->row_array()['user_id'];

    $get_response = http_POST('user/check_create_user', ['user_name' => 'christian', 'user_mail' => '123@gmai.com', 'user_username' => 'Malakani', 'user_password' => '123', 'user_password_validate' => '12']);

    $actual_values[$test_aspects[0]] = $get_response['status_code'];
    $actual_values[$test_aspects[1]] = $ci->db->query("SELECT user_id FROM users ORDER BY user_id DESC LIMIT 1")->row_array()['user_id'];

    test_unit($controller, $action, $test_name, $test_aspects, $expected_values, $actual_values);


    /*
     * Controller : User
     * Action : check_create_user
     * Description : Submit new user form while all the form fields are correctly filed but the reCAPTCHA is not checked.
     * Scenario : When the user submit the new user form, No data shoud be inserted into the users table
     * Expected HTTP Response Code : 200
     * Expected users table last id: the users table last user ID should be the same before and after the test
     */
    $test_name = "Submit new user form while all the form fields are correctly filed but the reCAPTCHA is not checked";
    $test_aspects = ["HTTP response code", "users table last ID"];

    $expected_values[$test_aspects[0]] = 200 . " " . status_code_description()[200];
    $expected_values[$test_aspects[1]] = $ci->db->query("SELECT user_id FROM users ORDER BY user_id DESC LIMIT 1")->row_array()['user_id'];

    $get_response = http_POST('user/check_create_user', ['user_name' => 'christian', 'user_mail' => '123@gmai.com', 'user_username' => 'Malakani', 'user_password' => '123', 'user_password_validate' => '123']);

    $actual_values[$test_aspects[0]] = $get_response['status_code'];
    $actual_values[$test_aspects[1]] = $ci->db->query("SELECT user_id FROM users ORDER BY user_id DESC LIMIT 1")->row_array()['user_id'];

    test_unit($controller, $action, $test_name, $test_aspects, $expected_values, $actual_values);


    /*
     * Controller : User
     * Action : check_create_user
     * Description : Submit new user form while all the form fields are correctly filed, the reCAPTCHA is checked, but the Username is already used.
     * Scenario : When the user submit the new user form, No data shoud be inserted into the users table
     * Expected HTTP Response Code : 200
     * Expected users table last id: the users table last user ID should be the same before and after the test
     */
    $test_name = "Submit new user form while all the form fields are correctly filed, the reCAPTCHA is checked, but the Username is already used";
    $test_aspects = ["HTTP response code", "users table last ID"];

    $expected_values[$test_aspects[0]] = 200 . " " . status_code_description()[200];
    $expected_values[$test_aspects[1]] = $ci->db->query("SELECT user_id FROM users ORDER BY user_id DESC LIMIT 1")->row_array()['user_id'];

    $get_response = http_POST('user/check_create_user', ['user_name' => 'chris', 'user_mail' => '123@gmai.com', 'user_username' => 'admin', 'user_password' => '123', 'user_password_validate' => '123', 'g-recaptcha-response' => 'relis_test']);

    $actual_values[$test_aspects[0]] = $get_response['status_code'];
    $actual_values[$test_aspects[1]] = $ci->db->query("SELECT user_id FROM users ORDER BY user_id DESC LIMIT 1")->row_array()['user_id'];

    test_unit($controller, $action, $test_name, $test_aspects, $expected_values, $actual_values);

    /*
     * Controller : User
     * Action : check_create_user
     * Description : Submit new user form while all the form fields are correctly filed and the reCAPTCHA is checked.
     * Scenario : When the user submit the new user form, a new user is created in the users table"
     * Expected HTTP Response Code : 200
     * Expected inserted user: the user that should be inserted in the users table
     * Expected inserted user confirmation: the user confirmation data that should be inserted in the user_creation table
     */
    $test_name = "Submit new user form while all the form fields are correctly filed and the reCAPTCHA is checked";
    $test_aspects = ["HTTP response code", "Created user", "User confirmation"];

    $user_name = "christian";
    $user_mail = "123@gmai.com";
    $user_username = "Malakani";
    $user_usergroup = "2";
    $created_by = "1";
    $user_state = "0";
    $user_active = "1";

    $get_response = http_POST('user/check_create_user', ['user_name' => $user_name, 'user_mail' => $user_mail, 'user_username' => $user_username, 'user_password' => '123', 'user_password_validate' => '123', 'g-recaptcha-response' => 'relis_test']);
    $sql_user = "Select user_id, user_name, user_mail, user_username, user_usergroup, created_by, user_state, user_active from users WHERE user_username = '" . $user_username . "'";
    $user_data = $ci->db->query($sql_user)->row_array();
    $sql_user_confirmation = "Select creation_user_id, confirmation_code, confirmation_try, user_creation_active from user_creation WHERE creation_user_id = " . $user_data['user_id'] . "";
    $user_confirmation_data = $ci->db->query($sql_user_confirmation)->row_array();

    $expected_values[$test_aspects[0]] = 200 . " " . status_code_description()[200];
    $expected_values[$test_aspects[1]] = json_encode(array("user_id" => $user_data['user_id'], "user_name" => $user_name, "user_mail" => $user_mail, "user_username" => $user_username, "user_usergroup" => $user_usergroup, "created_by" => $created_by, "user_state" => $user_state, "user_active" => $user_active));
    $expected_values[$test_aspects[2]] = json_encode(array("creation_user_id" => $user_data['user_id'], "confirmation_code" => $user_confirmation_data['confirmation_code'], "confirmation_try" => "0", "user_creation_active" => "1"));

    $actual_values[$test_aspects[0]] = $get_response['status_code'];
    $actual_values[$test_aspects[1]] = json_encode($user_data);
    $actual_values[$test_aspects[2]] = json_encode($user_confirmation_data);

    test_unit($controller, $action, $test_name, $test_aspects, $expected_values, $actual_values);


    //--------------------- validate_user action --------------------//

    $action = "validate_user";
    /*
     * Controller : User
     * Action : validate_user
     * Description : validate_user action displays the Form for account validation after signing up.
     * Expected HTTP Response Code : 200 (indicating a successful response from the server).
     */
    $test_name = "Check the displaying of the Form for account validation after signing up";
    $test_aspects = ["HTTP response code"];

    $get_response = http_GET('user/validate_user');

    $expected_values[$test_aspects[0]] = 200 . " " . status_code_description()[200];
    $actual_values[$test_aspects[0]] = $get_response['status_code'];

    test_unit($controller, $action, $test_name, $test_aspects, $expected_values, $actual_values);


    //--------------------- check_validation action --------------------//

    $action = "check_validation";

    /*
     * Controller : User
     * Action : check_validation
     * Description : Submit the account validation form with an empty validation_code field.
     * Scenario : When a user submit the account validation form with an empty validation_code field :
         - the user_sate field in the users table should remain 0
         - the user_creation_active field in the user_creation table should remain 1
     * Expected HTTP Response Code : 200 (indicating a successful response from the server).
     * Expected user_sate field : 0
     * Expected user_creation_active field : 1
     */
    $test_name = "Submit the account validation form with an empty validation_code field";
    $test_aspects = ["HTTP response code", "user_sate field in users table", "user_creation_active field in the user_creation table"];

    $expected_values[$test_aspects[0]] = 200 . " " . status_code_description()[200];
    $expected_values[$test_aspects[1]] = '0';
    $expected_values[$test_aspects[2]] = '1';

    $get_response = http_POST('user/check_validation', ['user_id' => $user_data['user_id'], 'validation_code' => '']);

    $actual_values[$test_aspects[0]] = $get_response['status_code'];
    $actual_values[$test_aspects[1]] = $ci->db->query("Select user_state from users WHERE user_id = '" . $user_data['user_id'] . "'")->row_array()['user_state'];
    $actual_values[$test_aspects[2]] = $ci->db->query("Select user_creation_active from user_creation WHERE creation_user_id = " . $user_data['user_id'] . "")->row_array()['user_creation_active'];

    test_unit($controller, $action, $test_name, $test_aspects, $expected_values, $actual_values);


    /*
     * Controller : User
     * Action : check_validation
     * Description : Submit the account validation form with a wrong validation code.
     * Scenario : When a user submit the account validation form with an empty validation_code field :
         - the user_sate field in the users table should remain 0
         - the user_creation_active field in the user_creation table should remain 1
     * Expected HTTP Response Code : 200 (indicating a successful response from the server).
     * Expected user_sate field : 0
     * Expected user_creation_active field : 1
     */
    $test_name = "Submit the account validation form with a wrong validation code";
    $test_aspects = ["HTTP response code", "user_sate field in users table", "user_creation_active field in the user_creation table"];

    $expected_values[$test_aspects[0]] = 200 . " " . status_code_description()[200];
    $expected_values[$test_aspects[1]] = '0';
    $expected_values[$test_aspects[2]] = '1';

    $get_response = http_POST('user/check_validation', ['user_id' => $user_data['user_id'], 'validation_code' => '1234']);

    $actual_values[$test_aspects[0]] = $get_response['status_code'];
    $actual_values[$test_aspects[1]] = $ci->db->query("Select user_state from users WHERE user_id = '" . $user_data['user_id'] . "'")->row_array()['user_state'];
    $actual_values[$test_aspects[2]] = $ci->db->query("Select user_creation_active from user_creation WHERE creation_user_id = " . $user_data['user_id'] . "")->row_array()['user_creation_active'];

    test_unit($controller, $action, $test_name, $test_aspects, $expected_values, $actual_values);


    /*
     * Controller : User
     * Action : check_validation
     * Description : Submit the account validation form with a correct validation code.
     * Scenario : When a user submit the account validation form with an empty validation_code field, the account should be validated :
         - the user_sate field in the users table should become 1
         - the user_creation_active field in the user_creation table should become 0
     * Expected HTTP Response Code : 200 (indicating a successful response from the server).
     * Expected user_sate field : 1
     * Expected user_creation_active field : 0
     */
    $test_name = "Submit the account validation form with a correct validation code";
    $test_aspects = ["HTTP response code", "user_sate field in users table", "user_creation_active field in the user_creation table"];

    $expected_values[$test_aspects[0]] = 200 . " " . status_code_description()[200];
    $expected_values[$test_aspects[1]] = '1';
    $expected_values[$test_aspects[2]] = '0';

    $get_response = http_POST('user/check_validation', ['user_id' => $user_data['user_id'], 'validation_code' => $user_confirmation_data['confirmation_code']]);

    $actual_values[$test_aspects[0]] = $get_response['status_code'];
    $actual_values[$test_aspects[1]] = $ci->db->query("Select user_state from users WHERE user_id = '" . $user_data['user_id'] . "'")->row_array()['user_state'];
    $actual_values[$test_aspects[2]] = $ci->db->query("Select user_creation_active from user_creation WHERE creation_user_id = " . $user_data['user_id'] . "")->row_array()['user_creation_active'];

    test_unit($controller, $action, $test_name, $test_aspects, $expected_values, $actual_values);


    //--------------------- login action --------------------//

    $action = "login";

    /*
     * Controller : User
     * Action : login
     * Description : This test verifies the behavior of the 'login' action when a user is not already logged in (there is no open user session).
     * Scenario : When a user is not logged in and clicks the 'Go to ReLiS' link, the user should be directed to the 'login' HTML form.
     * Expected HTTP Response Code : 200 (indicating a successful response from the server).
     */
    $test_name = "Go to login form page when user is not already logged in";
    $test_aspects = ["HTTP response code"];

    $get_response = http_GET('user/login');

    $expected_values[$test_aspects[0]] = 200 . " " . status_code_description()[200];
    $actual_values[$test_aspects[0]] = $get_response['status_code'];

    test_unit($controller, $action, $test_name, $test_aspects, $expected_values, $actual_values);

    /*
     * Controller : User
     * Action : login
     * Description : This test verifies the behavior of the 'login' action when a user is already logged in (there is an open user session).
     * Scenario : When a user is already logged in and clicks the 'Go to ReLiS' link, the user should be redirected to the 'home/index' url to be automatically logged in.
     * Expected HTTP Response Code : 307 (indicating a temporary Redirect code)
     * Expected URL : home (The expected URL after the user's action)
     */
    $test_name = "Go to login form page when user is already logged in";
    $test_aspects = ["HTTP response code", "Target URL"];

    $expected_values[$test_aspects[0]] = 307 . " " . status_code_description()[307];
    $expected_values[$test_aspects[1]] = 'home';

    $get_response = http_GET('user/login', ['Cookie: relis_session=dsqrv2lu49khh5g6gmvsq8pked3vabjt']);

    $actual_values[$test_aspects[0]] = $get_response['status_code'];
    preg_match('/8083\/(.*?)(\.html)?$/', $get_response['url'], $matches);
    $url = $matches[1];
    $actual_values[$test_aspects[1]] = $url;

    test_unit($controller, $action, $test_name, $test_aspects, $expected_values, $actual_values);


    //--------------------- check_form action --------------------//

    // $action = "check_form";

    // /*
    //  * Controller : User
    //  * Action : check_form
    //  * Description : Submit login form while all the form fields are empty.
    //  * Scenario : When the user submit the login form, the user should not be logged in
    //  * Expected HTTP Response Code : 200
    //  * Expected user session ID: 0
    //  */
    // $test_name = "Submit login form while all the form fields are empty";
    // $test_aspects = ["HTTP response code", "User session ID"];

    // $expected_values[$test_aspects[0]] = 200 . " " . status_code_description()[200];
    // $expected_values[$test_aspects[1]] = 0;

    // $get_response = http_POST('user/check_form', ['user_username' => '', 'user_password' => '']);

    // $actual_values[$test_aspects[0]] = $get_response['status_code'];
    // $actual_values[$test_aspects[1]] = $ci->session->userdata('user_id');

    // test_unit($controller, $action, $test_name, $test_aspects, $expected_values, $actual_values);

    // /*
    //  * Controller : User
    //  * Action : check_form
    //  * Description : Submit login form with empty username field and filled password field.
    //  * Scenario : When the user submit the login form, the user should not be logged in
    //  * Expected HTTP Response Code : 200
    //  * Expected user session ID: 0
    //  */
    // $test_name = "Submit login form with empty username field and filled password field";
    // $test_aspects = ["HTTP response code", "User session ID"];

    // $expected_values[$test_aspects[0]] = 200 . " " . status_code_description()[200];
    // $expected_values[$test_aspects[1]] = 0;

    // $get_response = http_POST('user/check_form', ['user_username' => '', 'user_password' => '123']);

    // $actual_values[$test_aspects[0]] = $get_response['status_code'];
    // $actual_values[$test_aspects[1]] = $ci->session->userdata('user_id');

    // test_unit($controller, $action, $test_name, $test_aspects, $expected_values, $actual_values);

    // /*
    //  * Controller : User
    //  * Action : check_form
    //  * Description : Submit login form with filled username field and empty password field.
    //  * Scenario : When the user submit the login form, the user should not be logged in
    //  * Expected HTTP Response Code : 200
    //  * Expected user session ID: 0
    //  */
    // $test_name = "Submit login form with filled username field and empty password field";
    // $test_aspects = ["HTTP response code", "User session ID"];

    // $expected_values[$test_aspects[0]] = 200 . " " . status_code_description()[200];
    // $expected_values[$test_aspects[1]] = 0;

    // $get_response = http_POST('user/check_form', ['user_username' => 'admin', 'user_password' => '']);

    // $actual_values[$test_aspects[0]] = $get_response['status_code'];
    // $actual_values[$test_aspects[1]] = $ci->session->userdata('user_id');

    // test_unit($controller, $action, $test_name, $test_aspects, $expected_values, $actual_values);

    // /*
    //  * Controller : User
    //  * Action : check_form
    //  * Description : Submit login form with correct username and wrong password.
    //  * Scenario : When the user submit the login form, the user should not be logged in
    //  * Expected HTTP Response Code : 200
    //  * Expected user session ID: 0
    //  */
    // $test_name = "Submit login form with correct username and wrong password";
    // $test_aspects = ["HTTP response code", "User session ID"];

    // $expected_values[$test_aspects[0]] = 200 . " " . status_code_description()[200];
    // $expected_values[$test_aspects[1]] = 0;

    // $get_response = http_POST('user/check_form', ['user_username' => 'admin', 'user_password' => '111']);

    // $actual_values[$test_aspects[0]] = $get_response['status_code'];
    // $actual_values[$test_aspects[1]] = $ci->session->userdata('user_id');

    // test_unit($controller, $action, $test_name, $test_aspects, $expected_values, $actual_values);

    // /*
    //  * Controller : User
    //  * Action : check_form
    //  * Description : Submit login form with wrong username and correct password.
    //  * Scenario : When the user submit the login form, the user should not be logged in
    //  * Expected HTTP Response Code : 200
    //  * Expected user session ID: 0
    //  */
    // $test_name = "Submit login form with wrong username and correct password";
    // $test_aspects = ["HTTP response code", "User session ID"];

    // $expected_values[$test_aspects[0]] = 200 . " " . status_code_description()[200];
    // $expected_values[$test_aspects[1]] = 0;

    // $get_response = http_POST('user/check_form', ['user_username' => 'add', 'user_password' => '123']);

    // $actual_values[$test_aspects[0]] = $get_response['status_code'];
    // $actual_values[$test_aspects[1]] = $ci->session->userdata('user_id');

    // test_unit($controller, $action, $test_name, $test_aspects, $expected_values, $actual_values);

    // /*
    //  * Controller : User
    //  * Action : check_form
    //  * Description : Submit login form with correct username and correct password.
    //  * Scenario : When the user submit the login form, the user should be logged in
    //  * Expected HTTP Response Code : 200
    //  * Expected user session ID: takes the user_id of the logged in user
    //  */
    // $test_name = "Submit login form with correct username and correct password";
    // $test_aspects = ["HTTP response code", "User session ID", "Target URL"];

    // $expected_values[$test_aspects[0]] = 303 . " " . status_code_description()[303];
    // $expected_values[$test_aspects[1]] = 1; // user_id of logged in user (admin)
    // $expected_values[$test_aspects[2]] = 'home';

    // $get_response = http_POST('user/check_form', ['user_username' => 'admin', 'user_password' => '123']);

    // $actual_values[$test_aspects[0]] = $get_response['status_code'];
    // $actual_values[$test_aspects[1]] = $ci->session->userdata('user_id');
    // preg_match('/8083\/(.*?)(\.html)?$/', $get_response['url'], $matches);
    // $url = $matches[1];
    // $actual_values[$test_aspects[2]] = $url;

    // test_unit($controller, $action, $test_name, $test_aspects, $expected_values, $actual_values);
}