<?php
/////////////////////////////////////// NEW ////////////////////////////////////

// TEST USER CONTROLLER
class User_unitTest
{
    private $controller;
    private $unitTest;
    private $ci;
    private $user;
    private $user_confirmation;

    function __construct()
    {
        $this->controller = "user";
        $this->unitTtest = new UnitTest();
        $this->ci = get_instance();
    }

    function run_tests()
    {
        $this->testDescriptionPageURL_httpResponseCode();
        $this->testNewUserPageURL_httpResponseCode();
        $this->testNewUserPageURL_userAlreadyConnected_httpResponseCode();
        $this->testNewUserPageURL_userAlreadyConnected_redirectedURL();
        $this->testNewUserFormWithEmptyFields_httpResponseCode();
        $this->testNewUserFormWithEmptyFields_tableLastID();
        $this->testNewUserFormWithInvalidEmail_httpResponseCode();
        $this->testNewUserFormWithInvalidEmail_tableLastID();
        $this->testNewUserFormWithUnmatchedPasswordAndConfirmPassword_httpResponseCode();
        $this->testNewUserFormWithUnmatchedPasswordAndConfirmPassword_tableLastID();
        $this->testNewUserFormWithRecaptchaNotChecked_httpResponseCode();
        $this->testNewUserFormWithRecaptchaNotChecked_tableLastID();
        $this->testNewUserFormWithUsedUsername_httpResponseCode();
        $this->testNewUserFormWithUsedUsername_tableLastID();
        $this->testNewUserFormWithcorrectFieldData_createdUser();
        $this->testNewUserFormWithcorrectFieldData_createdUserConfirmation();
        $this->testAccountValidationPage_httpResponseCode();
        $this->testAccountValidationFormWithEmptyValidationCode_httpResponseCode();
        $this->testAccountValidationFormWithEmptyValidationCode_userState();
        $this->testAccountValidationFormWithEmptyValidationCode_userCreationActive();
        $this->testAccountValidationFormWithWrongValidationCode_httpResponseCode();
        $this->testAccountValidationFormWithWrongValidationCode__userState();
        $this->testAccountValidationFormWithWrongValidationCode__userCreationActive();
        $this->testAccountValidationFormWithCorrectValidationCode_httpResponseCode();
        $this->testAccountValidationFormWithCorrectValidationCode_userState();
        $this->testAccountValidationFormWithCorrectValidationCode_userCreationActive();
        $this->testLoginPageURL_httpResponseCode();
        $this->testLoginPageURL_userAlreadyConnected_httpResponseCode();
        $this->testLoginPageURL_userAlreadyConnected_redirectedURL();
    }

    /*
     * Controller : User
     * Action : index
     * Description : This test verifies the behavior of the 'index' action.
     * Scenario : When a user navigate to "user/index" url they should be taken to the ReLiS description page.
     * Expected HTTP Response Code : 200 (indicating a successful response from the server).
     */
    private function testDescriptionPageURL_httpResponseCode()
    {
        $action = "indexx";
        $test_name = "Test description page url";
        $test_aspect = "Http response code";
        $expected_value = http_code()[200];
        $get_response = $this->unitTtest->response($this->controller, $action);
        $actual_value = $get_response['status_code'];
        $this->unitTtest->run_test($this->controller, $action, $test_name, $test_aspect, $expected_value, $actual_value);
    }

    /*
     * Controller : User
     * Action : new_user
     * Description : This test verifies the behavior of the 'new_user' action when a user is not already logged in (there is no open user session).
     * Scenario : When a user is not logged in and clicks the 'create user' link, the user should be directed to the 'new_user' HTML form to create a new user.
     * Expected HTTP Response Code : 200 (indicating a successful response from the server).
     */
    private function testNewUserPageURL_httpResponseCode()
    {
        $action = "new_user";
        $test_name = "Go to new user form page when user is not already logged in";
        $test_aspect = "Http response code";
        $expected_value = http_code()[200];
        $get_response = $this->unitTtest->response($this->controller, $action);
        $actual_value = $get_response['status_code'];
        $this->unitTtest->run_test($this->controller, $action, $test_name, $test_aspect, $expected_value, $actual_value);
    }

    /*
     * Controller : User
     * Action : new_user
     * Description : This test verifies the behavior of the 'new_user' action when a user is already logged in (there is an open user session).
     * Scenario : When a user is already logged in and clicks the 'create user' link, the user should be redirected to the 'home/index' url to be automatically logged in.
     * Expected HTTP Response Code : 307 (indicating a temporary Redirect code)
     */
    private function testNewUserPageURL_userAlreadyConnected_httpResponseCode()
    {
        // $action = "new_user";
        // $test_name = "Go to new user form page when user is already logged in";
        // $test_aspect = "Http response code";
        // $expected_value = http_code()[307];
        // $get_response = $this->unitTtest->response($this->controller, $action, ['Cookie: relis_session=dsqrv2lu49khh5g6gmvsq8pked3vabjt']);
        // $actual_value = $get_response['status_code'];
        // $this->unitTtest->run_test($this->controller, $action, $test_name, $test_aspect, $expected_value, $actual_value);
    }

    /*
     * Controller : User
     * Action : new_user
     * Description : This test verifies the behavior of the 'new_user' action when a user is already logged in (there is an open user session).
     * Scenario : When a user is already logged in and clicks the 'create user' link, the user should be redirected to the 'home/index' url to be automatically logged in.
     * Expected URL : home (The expected URL after the user's action)
     */
    private function testNewUserPageURL_userAlreadyConnected_redirectedURL()
    {
        // $action = "new_user";
        // $test_name = "Go to new user form page when user is already logged in";
        // $test_aspect = "Redirected URL";
        // $expected_value = "home";
        // $get_response = $this->unitTtest->response($this->controller, $action, ['Cookie: relis_session=dsqrv2lu49khh5g6gmvsq8pked3vabjt']);
        // preg_match('/8083\/(.*?)(\.html)?$/', $get_response['url'], $matches);
        // $url = $matches[1];
        // $actual_value = $url;
        // $this->unitTtest->run_test($this->controller, $action, $test_name, $test_aspect, $expected_value, $actual_value);
    }

    /*
     * Controller : User
     * Action : check_create_user
     * Description : Submit new user form while all the form fields are empty.
     * Scenario : When the user submit the new user form, No data shoud be inserted into the users table
     * Expected HTTP Response Code : 200
     */
    private function testNewUserFormWithEmptyFields_httpResponseCode()
    {
        $action = "check_create_user";
        $test_name = "Submit new user form while all the form fields are empty";
        $test_aspect = "Http response code";
        $expected_value = http_code()[200];
        $get_response = $this->unitTtest->response($this->controller, $action, ['user_name' => '', 'user_mail' => '', 'user_username' => '', 'user_password' => '', 'user_password_validate' => ''], "POST");
        $actual_value = $get_response['status_code'];
        $this->unitTtest->run_test($this->controller, $action, $test_name, $test_aspect, $expected_value, $actual_value);
    }

    /*
     * Controller : User
     * Action : check_create_user
     * Description : Submit new user form while all the form fields are empty.
     * Scenario : When the user submit the new user form, No data shoud be inserted into the users table
     * Expected users table last ID: the users table last user ID should be the same before and after the test
     */
    private function testNewUserFormWithEmptyFields_tableLastID()
    {
        $action = "check_create_user";
        $test_name = "Submit new user form while all the form fields are empty";
        $test_aspect = "Table last ID";
        $expected_value = $this->ci->db->query("SELECT user_id FROM users ORDER BY user_id DESC LIMIT 1")->row_array()['user_id'];
        $this->unitTtest->response($this->controller, $action, ['user_name' => '', 'user_mail' => '', 'user_username' => '', 'user_password' => '', 'user_password_validate' => ''], "POST");
        $actual_value = $this->ci->db->query("SELECT user_id FROM users ORDER BY user_id DESC LIMIT 1")->row_array()['user_id'];
        $this->unitTtest->run_test($this->controller, $action, $test_name, $test_aspect, $expected_value, $actual_value);
    }

    /*
     * Controller : User
     * Action : check_create_user
     * Description : Submit new user form while all the form fields are correctly filed but the email field is not valid.
     * Scenario : When the user submit the new user form, No data shoud be inserted into the users table
     * Expected HTTP Response Code : 200
     */
    private function testNewUserFormWithInvalidEmail_httpResponseCode()
    {
        $action = "check_create_user";
        $test_name = "Submit new user form while all the form fields are correctly filed but the email field is not valid";
        $test_aspect = "Http response code";
        $expected_value = http_code()[200];
        $get_response = $this->unitTtest->response($this->controller, $action, ['user_name' => 'christian', 'user_mail' => '123gmai.com', 'user_username' => 'Malakani', 'user_password' => '123', 'user_password_validate' => '123'], "POST");
        $actual_value = $get_response['status_code'];
        $this->unitTtest->run_test($this->controller, $action, $test_name, $test_aspect, $expected_value, $actual_value);
    }

    /*
     * Controller : User
     * Action : check_create_user
     * Description : Submit new user form while all the form fields are correctly filed but the email field is not valid.
     * Scenario : When the user submit the new user form, No data shoud be inserted into the users table
     * Expected users table last ID: the users table last user ID should be the same before and after the test
     */
    private function testNewUserFormWithInvalidEmail_tableLastID()
    {
        $action = "check_create_user";
        $test_name = "Submit new user form while all the form fields are correctly filed but the email field is not valid";
        $test_aspect = "Table last ID";
        $expected_value = $this->ci->db->query("SELECT user_id FROM users ORDER BY user_id DESC LIMIT 1")->row_array()['user_id'];
        $this->unitTtest->response($this->controller, $action, ['user_name' => 'christian', 'user_mail' => '123gmai.com', 'user_username' => 'Malakani', 'user_password' => '123', 'user_password_validate' => '123'], "POST");
        $actual_value = $this->ci->db->query("SELECT user_id FROM users ORDER BY user_id DESC LIMIT 1")->row_array()['user_id'];
        $this->unitTtest->run_test($this->controller, $action, $test_name, $test_aspect, $expected_value, $actual_value);
    }

    /*
     * Controller : User
     * Action : check_create_user
     * Description : Submit new user form while all the form fields are correctly filed but the validate password field doesn't match the password field.
     * Scenario : When the user submit the new user form, No data shoud be inserted into the users table
     * Expected HTTP Response Code : 200
     */
    private function testNewUserFormWithUnmatchedPasswordAndConfirmPassword_httpResponseCode()
    {
        $action = "check_create_user";
        $test_name = "Submit new user form while all the form fields are correctly filed but the validate password field doesn't match the password field";
        $test_aspect = "Http response code";
        $expected_value = http_code()[200];
        $get_response = $this->unitTtest->response($this->controller, $action, ['user_name' => 'christian', 'user_mail' => '123@gmai.com', 'user_username' => 'Malakani', 'user_password' => '123', 'user_password_validate' => '12'], "POST");
        $actual_value = $get_response['status_code'];
        $this->unitTtest->run_test($this->controller, $action, $test_name, $test_aspect, $expected_value, $actual_value);
    }

    /*
     * Controller : User
     * Action : check_create_user
     * Description : Submit new user form while all the form fields are correctly filed but the validate password field doesn't match the password field.
     * Scenario : When the user submit the new user form, No data shoud be inserted into the users table
     * Expected users table last id: the users table last user ID should be the same before and after the test
     */
    private function testNewUserFormWithUnmatchedPasswordAndConfirmPassword_tableLastID()
    {
        $action = "check_create_user";
        $test_name = "Submit new user form while all the form fields are correctly filed but the validate password field doesn't match the password field";
        $test_aspect = "Table last ID";
        $expected_value = $this->ci->db->query("SELECT user_id FROM users ORDER BY user_id DESC LIMIT 1")->row_array()['user_id'];
        $this->unitTtest->response($this->controller, $action, ['user_name' => 'christian', 'user_mail' => '123@gmai.com', 'user_username' => 'Malakani', 'user_password' => '123', 'user_password_validate' => '12'], "POST");
        $actual_value = $this->ci->db->query("SELECT user_id FROM users ORDER BY user_id DESC LIMIT 1")->row_array()['user_id'];
        $this->unitTtest->run_test($this->controller, $action, $test_name, $test_aspect, $expected_value, $actual_value);
    }

    /*
     * Controller : User
     * Action : check_create_user
     * Description : Submit new user form while all the form fields are correctly filed but the reCAPTCHA is not checked.
     * Scenario : When the user submit the new user form, No data shoud be inserted into the users table
     * Expected HTTP Response Code : 200
     */
    private function testNewUserFormWithRecaptchaNotChecked_httpResponseCode()
    {
        $action = "check_create_user";
        $test_name = "Submit new user form while all the form fields are correctly filed but the reCAPTCHA is not checked";
        $test_aspect = "Http response code";
        $expected_value = http_code()[200];
        $get_response = $this->unitTtest->response($this->controller, $action, ['user_name' => 'christian', 'user_mail' => '123@gmai.com', 'user_username' => 'Malakani', 'user_password' => '123', 'user_password_validate' => '123'], "POST");
        $actual_value = $get_response['status_code'];
        $this->unitTtest->run_test($this->controller, $action, $test_name, $test_aspect, $expected_value, $actual_value);
    }

    /*
     * Controller : User
     * Action : check_create_user
     * Description : Submit new user form while all the form fields are correctly filed but the reCAPTCHA is not checked.
     * Scenario : When the user submit the new user form, No data shoud be inserted into the users table
     * Expected users table last id: the users table last user ID should be the same before and after the test
     */
    private function testNewUserFormWithRecaptchaNotChecked_tableLastID()
    {
        $action = "check_create_user";
        $test_name = "Submit new user form while all the form fields are correctly filed but the reCAPTCHA is not checked";
        $test_aspect = "Table last ID";
        $expected_value = $this->ci->db->query("SELECT user_id FROM users ORDER BY user_id DESC LIMIT 1")->row_array()['user_id'];
        $this->unitTtest->response($this->controller, $action, ['user_name' => 'christian', 'user_mail' => '123@gmai.com', 'user_username' => 'Malakani', 'user_password' => '123', 'user_password_validate' => '123'], "POST");
        $actual_value = $this->ci->db->query("SELECT user_id FROM users ORDER BY user_id DESC LIMIT 1")->row_array()['user_id'];
        $this->unitTtest->run_test($this->controller, $action, $test_name, $test_aspect, $expected_value, $actual_value);
    }

    /*
     * Controller : User
     * Action : check_create_user
     * Description : Submit new user form while all the form fields are correctly filed, the reCAPTCHA is checked, but the Username is already used.
     * Scenario : When the user submit the new user form, No data shoud be inserted into the users table
     * Expected HTTP Response Code : 200
     */
    private function testNewUserFormWithUsedUsername_httpResponseCode()
    {
        $action = "check_create_user";
        $test_name = "Submit new user form while all the form fields are correctly filed, the reCAPTCHA is checked, but the Username is already used";
        $test_aspect = "Http response code";
        $expected_value = http_code()[200];
        $get_response = $this->unitTtest->response($this->controller, $action, ['user_name' => 'chris', 'user_mail' => '123@gmai.com', 'user_username' => 'admin', 'user_password' => '123', 'user_password_validate' => '123', 'g-recaptcha-response' => 'relis_test'], "POST");
        $actual_value = $get_response['status_code'];
        $this->unitTtest->run_test($this->controller, $action, $test_name, $test_aspect, $expected_value, $actual_value);
    }

    /*
     * Controller : User
     * Action : check_create_user
     * Description : Submit new user form while all the form fields are correctly filed, the reCAPTCHA is checked, but the Username is already used.
     * Scenario : When the user submit the new user form, No data shoud be inserted into the users table
     * Expected users table last id: the users table last user ID should be the same before and after the test
     */
    private function testNewUserFormWithUsedUsername_tableLastID()
    {
        $action = "check_create_user";
        $test_name = "Submit new user form while all the form fields are correctly filed, the reCAPTCHA is checked, but the Username is already used";
        $test_aspect = "Table last ID";
        $expected_value = $this->ci->db->query("SELECT user_id FROM users ORDER BY user_id DESC LIMIT 1")->row_array()['user_id'];
        $this->unitTtest->response($this->controller, $action, ['user_name' => 'chris', 'user_mail' => '123@gmai.com', 'user_username' => 'admin', 'user_password' => '123', 'user_password_validate' => '123', 'g-recaptcha-response' => 'relis_test'], "POST");
        $actual_value = $this->ci->db->query("SELECT user_id FROM users ORDER BY user_id DESC LIMIT 1")->row_array()['user_id'];
        $this->unitTtest->run_test($this->controller, $action, $test_name, $test_aspect, $expected_value, $actual_value);
    }

    /*
     * Controller : User
     * Action : check_create_user
     * Description : Submit new user form while all the form fields are correctly filed and the reCAPTCHA is checked.
     * Scenario : When the user submit the new user form, a new user is created in the users table"
     * Expected HTTP Response Code : 200
     */
    private function testNewUserFormWithcorrectFieldData_httpResponseCode()
    {
        $action = "check_create_user";
        $test_name = "Submit new user form while all the form fields are correctly filed and the reCAPTCHA is checked";
        $test_aspect = "Http response code";
        $expected_value = http_code()[200];

        $user_name = "christian";
        $user_mail = "123@gmai.com";
        $user_username = "Malakani";
        $user_usergroup = "2";
        $created_by = "1";
        $user_state = "0";
        $user_active = "1";
        $get_response = $this->unitTtest->response($this->controller, $action, ['user_name' => $user_name, 'user_mail' => $user_mail, 'user_username' => $user_username, 'user_password' => '123', 'user_password_validate' => '123', 'g-recaptcha-response' => 'relis_test'], "POST");
        $actual_value = $get_response['status_code'];
        $this->unitTtest->run_test($this->controller, $action, $test_name, $test_aspect, $expected_value, $actual_value);

        return ["user_name" => $user_name, "user_mail" => $user_mail, "user_username" => $user_username, "user_usergroup" => $user_usergroup, "created_by" => $created_by, "user_state" => $user_state, "user_active" => $user_active];
    }

    /*
     * Controller : User
     * Action : check_create_user
     * Description : Submit new user form while all the form fields are correctly filed and the reCAPTCHA is checked.
     * Scenario : When the user submit the new user form, a new user is created in the users table"
     * Expected created user: the user that should be inserted in the users table
     */
    private function testNewUserFormWithcorrectFieldData_createdUser()
    {
        $user = $this->testNewUserFormWithcorrectFieldData_httpResponseCode();
        $action = "check_create_user";
        $test_name = "Submit new user form while all the form fields are correctly filed and the reCAPTCHA is checked";
        $test_aspect = "Created user";
        $expected_value = json_encode($user);

        $sql_user = "Select user_id, user_name, user_mail, user_username, user_usergroup, created_by, user_state, user_active from users WHERE user_username = '" . $user['user_username'] . "'";
        $user_data = $this->ci->db->query($sql_user)->row_array();
        $this->user = $user_data;
        unset($user_data['user_id']);
        $actual_value = json_encode($user_data);
        $this->unitTtest->run_test($this->controller, $action, $test_name, $test_aspect, $expected_value, $actual_value);
    }

    /*
     * Controller : User
     * Action : check_create_user
     * Description : Submit new user form while all the form fields are correctly filed and the reCAPTCHA is checked.
     * Scenario : When the user submit the new user form, a new user is created in the users table"
     * Expected created user confirmation: the user confirmation data that should be inserted in the user_creation table
     */
    private function testNewUserFormWithcorrectFieldData_createdUserConfirmation()
    {
        $action = "check_create_user";
        $test_name = "Submit new user form while all the form fields are correctly filed and the reCAPTCHA is checked";
        $test_aspect = "Created user confirmation";

        $sql_user_confirmation = "Select creation_user_id, confirmation_code, confirmation_try, user_creation_active from user_creation WHERE creation_user_id = " . $this->user['user_id'] . "";
        $user_confirmation_data = $this->ci->db->query($sql_user_confirmation)->row_array();
        $this->user_confirmation = $user_confirmation_data;

        $expected_value = json_encode(array("creation_user_id" => $this->user['user_id'], "confirmation_code" => $user_confirmation_data['confirmation_code'], "confirmation_try" => "0", "user_creation_active" => "1"));
        $actual_value = json_encode($user_confirmation_data);
        $this->unitTtest->run_test($this->controller, $action, $test_name, $test_aspect, $expected_value, $actual_value);
    }

    /*
     * Controller : User
     * Action : validate_user
     * Description : validate_user action displays the Form for account validation after signing up.
     * Expected HTTP Response Code : 200 (indicating a successful response from the server).
     */
    private function testAccountValidationPage_httpResponseCode()
    {
        $action = "validate_user";
        $test_name = "Check the displaying of the Form for account validation after signing up";
        $test_aspect = "Http response code";
        $expected_value = http_code()[200];
        $get_response = $this->unitTtest->response($this->controller, $action);
        $actual_value = $get_response['status_code'];
        $this->unitTtest->run_test($this->controller, $action, $test_name, $test_aspect, $expected_value, $actual_value);
    }

    /*
     * Controller : User
     * Action : check_validation
     * Description : Submit the account validation form with an empty validation_code field.
     * Scenario : When a user submit the account validation form with an empty validation_code field :
     *      - the user_sate field in the users table should remain 0
     *      - the user_creation_active field in the user_creation table should remain 1
     * Expected HTTP Response Code : 200 (indicating a successful response from the server).
     */
    private function testAccountValidationFormWithEmptyValidationCode_httpResponseCode()
    {
        $action = "check_validation";
        $test_name = "Submit the account validation form with an empty validation_code field";
        $test_aspect = "Http response code";
        $expected_value = http_code()[200];
        $get_response = $this->unitTtest->response($this->controller, $action, ['user_id' => $this->user['user_id'], 'validation_code' => ''], "POST");
        $actual_value = $get_response['status_code'];
        $this->unitTtest->run_test($this->controller, $action, $test_name, $test_aspect, $expected_value, $actual_value);
    }

    /*
     * Controller : User
     * Action : check_validation
     * Description : Submit the account validation form with an empty validation_code field.
     * Scenario : When a user submit the account validation form with an empty validation_code field :
     *      - the user_sate field in the users table should remain 0
     *      - the user_creation_active field in the user_creation table should remain 1
     * Expected user_sate field : 0
     */
    private function testAccountValidationFormWithEmptyValidationCode_userState()
    {
        $action = "check_validation";
        $test_name = "Submit the account validation form with an empty validation_code field";
        $test_aspect = "user_state field in users table";
        $expected_value = '0';
        $this->unitTtest->response($this->controller, $action, ['user_id' => $this->user['user_id'], 'validation_code' => ''], "POST");
        $actual_value = $this->ci->db->query("Select user_state from users WHERE user_id = '" . $this->user['user_id'] . "'")->row_array()['user_state'];
        $this->unitTtest->run_test($this->controller, $action, $test_name, $test_aspect, $expected_value, $actual_value);
    }

    /*
     * Controller : User
     * Action : check_validation
     * Description : Submit the account validation form with an empty validation_code field.
     * Scenario : When a user submit the account validation form with an empty validation_code field :
     *      - the user_sate field in the users table should remain 0
     *      - the user_creation_active field in the user_creation table should remain 1
     * Expected user_creation_active field : 1
     */
    private function testAccountValidationFormWithEmptyValidationCode_userCreationActive()
    {
        $action = "check_validation";
        $test_name = "Submit the account validation form with an empty validation_code field";
        $test_aspect = "user_creation_active field in user_creation table";
        $expected_value = '1';
        $this->unitTtest->response($this->controller, $action, ['user_id' => $this->user['user_id'], 'validation_code' => ''], "POST");
        $actual_value = $this->ci->db->query("Select user_creation_active from user_creation WHERE creation_user_id = " . $this->user['user_id'] . "")->row_array()['user_creation_active'];
        $this->unitTtest->run_test($this->controller, $action, $test_name, $test_aspect, $expected_value, $actual_value);
    }

    /*
     * Controller : User
     * Action : check_validation
     * Description : Submit the account validation form with a wrong validation code.
     * Scenario : When a user submit the account validation form with an wrong validation_code field :
     *      - the user_sate field in the users table should remain 0
     *      - the user_creation_active field in the user_creation table should remain 1
     * Expected HTTP Response Code : 200 (indicating a successful response from the server).
     */
    private function testAccountValidationFormWithWrongValidationCode_httpResponseCode()
    {
        $action = "check_validation";
        $test_name = "Submit the account validation form with a wrong validation code";
        $test_aspect = "Http response code";
        $expected_value = http_code()[200];
        $get_response = $this->unitTtest->response($this->controller, $action, ['user_id' => $this->user['user_id'], 'validation_code' => '1234'], "POST");
        $actual_value = $get_response['status_code'];
        $this->unitTtest->run_test($this->controller, $action, $test_name, $test_aspect, $expected_value, $actual_value);
    }

    /*
     * Controller : User
     * Action : check_validation
     * Description : Submit the account validation form with a wrong validation code.
     * Scenario : When a user submit the account validation form with an wrong validation_code field :
     *      - the user_sate field in the users table should remain 0
     *      - the user_creation_active field in the user_creation table should remain 1
     * Expected user_sate field : 0
     */
    private function testAccountValidationFormWithWrongValidationCode__userState()
    {
        $action = "check_validation";
        $test_name = "Submit the account validation form with a wrong validation code";
        $test_aspect = "user_state field in users table";
        $expected_value = '0';
        $this->unitTtest->response($this->controller, $action, ['user_id' => $this->user['user_id'], 'validation_code' => '1234'], "POST");
        $actual_value = $this->ci->db->query("Select user_state from users WHERE user_id = '" . $this->user['user_id'] . "'")->row_array()['user_state'];
        $this->unitTtest->run_test($this->controller, $action, $test_name, $test_aspect, $expected_value, $actual_value);
    }

    /*
     * Controller : User
     * Action : check_validation
     * Description : Submit the account validation form with a wrong validation code.
     * Scenario : When a user submit the account validation form with an wrong validation_code field :
     *      - the user_sate field in the users table should remain 0
     *      - the user_creation_active field in the user_creation table should remain 1
     * Expected user_creation_active field : 1
     */
    private function testAccountValidationFormWithWrongValidationCode__userCreationActive()
    {
        $action = "check_validation";
        $test_name = "Submit the account validation form with a wrong validation code";
        $test_aspect = "user_creation_active field in user_creation table";
        $expected_value = '1';
        $this->unitTtest->response($this->controller, $action, ['user_id' => $this->user['user_id'], 'validation_code' => '1234'], "POST");
        $actual_value = $this->ci->db->query("Select user_creation_active from user_creation WHERE creation_user_id = " . $this->user['user_id'] . "")->row_array()['user_creation_active'];
        $this->unitTtest->run_test($this->controller, $action, $test_name, $test_aspect, $expected_value, $actual_value);
    }

    /*
     * Controller : User
     * Action : check_validation
     * Description : Submit the account validation form with a correct validation code.
     * Scenario : When a user submit the account validation form with an correct validation_code field, the account should be validated :
     *      - the user_sate field in the users table should become 1
     *      - the user_creation_active field in the user_creation table should become 0
     * Expected HTTP Response Code : 200 (indicating a successful response from the server).
     */
    private function testAccountValidationFormWithCorrectValidationCode_httpResponseCode()
    {
        $action = "check_validation";
        $test_name = "Submit the account validation form with a correct validation code";
        $test_aspect = "Http response code";
        $expected_value = http_code()[200];
        $get_response = $this->unitTtest->response($this->controller, $action, ['user_id' => $this->user['user_id'], 'validation_code' => $this->user_confirmation['confirmation_code']], "POST");
        $actual_value = $get_response['status_code'];
        $this->unitTtest->run_test($this->controller, $action, $test_name, $test_aspect, $expected_value, $actual_value);
    }

    /*
     * Controller : User
     * Action : check_validation
     * Description : Submit the account validation form with a correct validation code.
     * Scenario : When a user submit the account validation form with an correct validation_code field, the account should be validated :
     *      - the user_sate field in the users table should become 1
     *      - the user_creation_active field in the user_creation table should become 0
     * Expected user_sate field : 1
     */
    private function testAccountValidationFormWithCorrectValidationCode_userState()
    {
        $action = "check_validation";
        $test_name = "Submit the account validation form with a correct validation code";
        $test_aspect = "user_state field in users table";
        $expected_value = '1';
        $actual_value = $this->ci->db->query("Select user_state from users WHERE user_id = '" . $this->user['user_id'] . "'")->row_array()['user_state'];
        $this->unitTtest->run_test($this->controller, $action, $test_name, $test_aspect, $expected_value, $actual_value);
    }

    /*
     * Controller : User
     * Action : check_validation
     * Description : Submit the account validation form with a correct validation code.
     * Scenario : When a user submit the account validation form with an correct validation_code field, the account should be validated :
     *      - the user_sate field in the users table should become 1
     *      - the user_creation_active field in the user_creation table should become 0
     * Expected user_creation_active field : 0
     */
    private function testAccountValidationFormWithCorrectValidationCode_userCreationActive()
    {
        $action = "check_validation";
        $test_name = "Submit the account validation form with a correct validation code";
        $test_aspect = "user_creation_active field in user_creation table";
        $expected_value = '0';
        $actual_value = $this->ci->db->query("Select user_creation_active from user_creation WHERE creation_user_id = " . $this->user['user_id'] . "")->row_array()['user_creation_active'];
        $this->unitTtest->run_test($this->controller, $action, $test_name, $test_aspect, $expected_value, $actual_value);
    }

    /*
     * Controller : User
     * Action : login
     * Description : This test verifies the behavior of the 'login' action when a user is not already logged in (there is no open user session).
     * Scenario : When a user is not logged in and clicks the 'Go to ReLiS' link, the user should be directed to the 'login' HTML form.
     * Expected HTTP Response Code : 200 (indicating a successful response from the server).
     */
    private function testLoginPageURL_httpResponseCode()
    {
        $action = "login";
        $test_name = "Go to login form page when user is not already logged in";
        $test_aspect = "Http response code";
        $expected_value = http_code()[200];
        $get_response = $this->unitTtest->response($this->controller, $action);
        $actual_value = $get_response['status_code'];
        $this->unitTtest->run_test($this->controller, $action, $test_name, $test_aspect, $expected_value, $actual_value);
    }

    /*
     * Controller : User
     * Action : login
     * Description : This test verifies the behavior of the 'login' action when a user is already logged in (there is an open user session).
     * Scenario : When a user is already logged in and clicks the 'Go to ReLiS' link, the user should be redirected to the 'home/index' url to be automatically logged in.
     * Expected HTTP Response Code : 307 (indicating a temporary Redirect code)
     */
    private function testLoginPageURL_userAlreadyConnected_httpResponseCode()
    {
        // $action = "login";
        // $test_name = "Go to login form page when user is already logged in";
        // $test_aspect = "Http response code";
        // $expected_value = http_code()[307];
        // $get_response = $this->unitTtest->response($this->controller, $action, ['Cookie: relis_session=dsqrv2lu49khh5g6gmvsq8pked3vabjt']);
        // $actual_value = $get_response['status_code'];
        // $this->unitTtest->run_test($this->controller, $action, $test_name, $test_aspect, $expected_value, $actual_value);
    }

    /*
     * Controller : User
     * Action : login
     * Description : This test verifies the behavior of the 'login' action when a user is already logged in (there is an open user session).
     * Scenario : When a user is already logged in and clicks the 'Go to ReLiS' link, the user should be redirected to the 'home/index' url to be automatically logged in.
     * Expected URL : home (The expected URL after the user's action)
     */
    private function testLoginPageURL_userAlreadyConnected_redirectedURL()
    {
        // $action = "login";
        // $test_name = "Go to login form page when user is already logged in";
        // $test_aspect = "Redirected URL";
        // $expected_value = 'home';
        // $get_response = $this->unitTtest->response($this->controller, $action, ['Cookie: relis_session=dsqrv2lu49khh5g6gmvsq8pked3vabjt']);
        // preg_match('/8083\/(.*?)(\.html)?$/', $get_response['url'], $matches);
        // $url = $matches[1];
        // $actual_value = $url;
        // $this->unitTtest->run_test($this->controller, $action, $test_name, $test_aspect, $expected_value, $actual_value);
    }
}




// --------------------- check_form action --------------------//

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

// $expected_value[$test_aspects[0]] = 200 . " " . status_code_description()[200];
// $expected_value[$test_aspects[1]] = 0;

// $get_response = http_POST('user/check_form', ['user_username' => '', 'user_password' => '']);

// $actual_value[$test_aspects[0]] = $get_response['status_code'];
// $actual_value[$test_aspects[1]] = $ci->session->userdata('user_id');

// test_unit($controller, $action, $test_name, $test_aspects, $expected_value, $actual_value);

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

// $expected_value[$test_aspects[0]] = 200 . " " . status_code_description()[200];
// $expected_value[$test_aspects[1]] = 0;

// $get_response = http_POST('user/check_form', ['user_username' => '', 'user_password' => '123']);

// $actual_value[$test_aspects[0]] = $get_response['status_code'];
// $actual_value[$test_aspects[1]] = $ci->session->userdata('user_id');

// test_unit($controller, $action, $test_name, $test_aspects, $expected_value, $actual_value);

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

// $expected_value[$test_aspects[0]] = 200 . " " . status_code_description()[200];
// $expected_value[$test_aspects[1]] = 0;

// $get_response = http_POST('user/check_form', ['user_username' => 'admin', 'user_password' => '']);

// $actual_value[$test_aspects[0]] = $get_response['status_code'];
// $actual_value[$test_aspects[1]] = $ci->session->userdata('user_id');

// test_unit($controller, $action, $test_name, $test_aspects, $expected_value, $actual_value);

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

// $expected_value[$test_aspects[0]] = 200 . " " . status_code_description()[200];
// $expected_value[$test_aspects[1]] = 0;

// $get_response = http_POST('user/check_form', ['user_username' => 'admin', 'user_password' => '111']);

// $actual_value[$test_aspects[0]] = $get_response['status_code'];
// $actual_value[$test_aspects[1]] = $ci->session->userdata('user_id');

// test_unit($controller, $action, $test_name, $test_aspects, $expected_value, $actual_value);

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

// $expected_value[$test_aspects[0]] = 200 . " " . status_code_description()[200];
// $expected_value[$test_aspects[1]] = 0;

// $get_response = http_POST('user/check_form', ['user_username' => 'add', 'user_password' => '123']);

// $actual_value[$test_aspects[0]] = $get_response['status_code'];
// $actual_value[$test_aspects[1]] = $ci->session->userdata('user_id');

// test_unit($controller, $action, $test_name, $test_aspects, $expected_value, $actual_value);

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

// $expected_value[$test_aspects[0]] = 303 . " " . status_code_description()[303];
// $expected_value[$test_aspects[1]] = 1; // user_id of logged in user (admin)
// $expected_value[$test_aspects[2]] = 'home';

// $get_response = http_POST('user/check_form', ['user_username' => 'admin', 'user_password' => '123']);

// $actual_value[$test_aspects[0]] = $get_response['status_code'];
// $actual_value[$test_aspects[1]] = $ci->session->userdata('user_id');
// preg_match('/8083\/(.*?)(\.html)?$/', $get_response['url'], $matches);
// $url = $matches[1];
// $actual_value[$test_aspects[2]] = $url;

// test_unit($controller, $action, $test_name, $test_aspects, $expected_value, $actual_value);
