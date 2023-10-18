<?php
/////////////////////////////////////// NEW ////////////////////////////////////

// TEST USER CONTROLLER
class User_unitTest
{
    private $controller;
    private $http_client;
    private $ci;
    private $user;
    private $user_confirmation;

    function __construct()
    {
        $this->controller = "user";
        $this->http_client = new Http_client();
        $this->ci = get_instance();
    }

    function run_tests()
    {
        $this->testDescriptionPageURL_httpResponseCode();
        $this->user_helpPageURl();
        $this->helpPageURl();
        $this->helpDetPageURl();
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
        $this->testsubmitLogin_allFieldsEmpty_httpResponseCode();
        $this->testsubmitLogin_allFieldsEmpty_userSessionCookie();
        $this->testsubmitLogin_EmptyUsername_userSessionCookie();
        $this->submitLogin_EmptyPassword_userSessionCookie();
        $this->submitLogin_wrongPassword_userSessionCookie();
        $this->submitLogin_wrongUsername_userSessionCookie();
        $this->submitLogin_nonValidatedAccount_userSessionCookie();
        $this->submitLogin_correctUsernameAndPassword_userSessionCookie();
        $this->loginAsDemoUser();
        $this->logout();
    }

    /*
     * Controller : User
     * Action : index
     * Description : This test verifies the behavior of the 'index' action.
     * Scenario : When the user navigate to "user/index" url they should be taken to the ReLiS description page.
     * Expected HTTP Response Code : 200 OK (indicating a successful response from the server).
     */
    private function testDescriptionPageURL_httpResponseCode()
    {
        $action = "index";
        $test_name = "Test description page url";
        $test_aspect = "Http response code";
        $expected_value = http_code()[200];
        $response = $this->http_client->response($this->controller, $action);
        $actual_value = $response->getStatusCode() . ' ' . $response->getReasonPhrase();
        run_test($this->controller, $action, $test_name, $test_aspect, $expected_value, $actual_value);
    }

    /*
     * Controller : User
     * Action : user_help
     * Scenario : When the user navigate to "user/user_help" url they should be taken to the ReLiS help page (Getting Started page).
     * Expected HTTP Response Code : 200 OK (indicating a successful response from the server).
     */
    private function user_helpPageURl()
    {
        $action = "user_help";
        $test_name = "Test user help page url";
        $test_aspect = "Http response code";
        $expected_value = http_code()[200];
        $response = $this->http_client->response($this->controller, $action);
        $actual_value = $response->getStatusCode() . ' ' . $response->getReasonPhrase();
        run_test($this->controller, $action, $test_name, $test_aspect, $expected_value, $actual_value);
    }

    /*
     * Controller : User
     * Action : help
     * Scenario : When the user navigate to "user/help" url they should be taken to the ReLiS help page (Getting Started page).
     * Expected HTTP Response Code : 200 OK (indicating a successful response from the server).
     */
    private function helpPageURl()
    {
        $action = "help";
        $test_name = "Test help page url";
        $test_aspect = "Http response code";
        $expected_value = http_code()[200];
        $response = $this->http_client->response($this->controller, $action);
        $actual_value = $response->getStatusCode() . ' ' . $response->getReasonPhrase();
        run_test($this->controller, $action, $test_name, $test_aspect, $expected_value, $actual_value);
    }

    /*
     * Controller : User
     * Action : help_det
     * Scenario : When the user navigate to "user/help_det/[number]" url they should be taken to the ReLiS tutorial page for a specific relis step.
     * Expected HTTP Response Code : 200 OK (indicating a successful response from the server).
     */
    private function helpDetPageURl()
    {
        $action = "help_det";
        $test_name = "Test tutorial page url";
        $test_aspect = "Http response code";
        $expected_value = http_code()[200];
        $response = $this->http_client->response($this->controller, $action . '/1');
        $actual_value = $response->getStatusCode() . ' ' . $response->getReasonPhrase();
        run_test($this->controller, $action, $test_name, $test_aspect, $expected_value, $actual_value);
    }

    /*
     * Controller : User
     * Action : new_user
     * Description : This test verifies the behavior of the 'new_user' action (Navigate to new user form page) when a user is not already logged in (there is no open user session).
     * Scenario : When the user is not logged in and clicks the 'create user' link, the user should be directed to the 'new_user' HTML form to create a new user.
     * Expected HTTP Response Code : 200 OK (indicating a successful response from the server).
     */
    private function testNewUserPageURL_httpResponseCode()
    {
        $action = "new_user";
        $test_name = "Go to new user form page when user is not already logged in";
        $test_aspect = "Http response code";
        $expected_value = http_code()[200];
        //unset authentication cookie if exists
        $this->http_client->unsetCookie('relis_session');
        $response = $this->http_client->response($this->controller, $action);
        $actual_value = $response->getStatusCode() . ' ' . $response->getReasonPhrase();
        run_test($this->controller, $action, $test_name, $test_aspect, $expected_value, $actual_value);
    }

    /*
     * Controller : User
     * Action : new_user
     * Description : This test verifies the behavior of the 'new_user' action (Navigate to new user form page) when a user is already logged in (there is an open user session).
     * Scenario : When the user is already logged in and clicks the 'create user' link (Navigate to new user form page), the user should be redirected to the 'home/index' url to be automatically logged in.
     * Expected HTTP Response Code : 307 (indicating a temporary Redirect code)
     */
    private function testNewUserPageURL_userAlreadyConnected_httpResponseCode()
    {
        $action = "new_user";
        $test_name = "Go to new user form page when user is already logged in";
        $test_aspect = "Http response code";
        $expected_value = http_code()[307];
        //Login first
        $this->http_client->response($this->controller, "check_form", ['user_username' => 'admin', 'user_password' => '123'], "POST");
        //Navigate to new user form page
        $response = $this->http_client->response($this->controller, $action);
        $actual_value = $response->getStatusCode() . ' ' . $response->getReasonPhrase();
        run_test($this->controller, $action, $test_name, $test_aspect, $expected_value, $actual_value);
        //unset authentication cookie
        $this->http_client->unsetCookie('relis_session');
    }

    /*
     * Controller : User
     * Action : new_user
     * Description : This test verifies the behavior of the 'new_user' action (Navigate to new user form page) when a user is already logged in (there is an open user session).
     * Scenario : When the user is already logged in and clicks the 'create user' link (Navigate to new user form page), the user should be redirected to the 'home/index' url to be automatically logged in.
     * Expected URL : home (The expected URL after the user's action)
     */
    private function testNewUserPageURL_userAlreadyConnected_redirectedURL()
    {
        $action = "new_user";
        $test_name = "Go to new user form page when user is already logged in";
        $test_aspect = "Redirected URL";
        $expected_value = "home";
        //Login first
        $this->http_client->response($this->controller, "check_form", ['user_username' => 'admin', 'user_password' => '123'], "POST");
        //Navigate to new user form page
        $response = $this->http_client->response($this->controller, $action);

        preg_match('/8083\/(.*?)(\.html)?$/', $response->getHeaders()['Location'][0], $matches);
        $url = $matches[1];
        $actual_value = $url;
        run_test($this->controller, $action, $test_name, $test_aspect, $expected_value, $actual_value);
        //unset authentication cookie
        $this->http_client->unsetCookie('relis_session');
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
        $response = $this->http_client->response($this->controller, $action, ['user_name' => '', 'user_mail' => '', 'user_username' => '', 'user_password' => '', 'user_password_validate' => ''], "POST");
        $actual_value = $response->getStatusCode() . ' ' . $response->getReasonPhrase();
        run_test($this->controller, $action, $test_name, $test_aspect, $expected_value, $actual_value);
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
        $this->http_client->response($this->controller, $action, ['user_name' => '', 'user_mail' => '', 'user_username' => '', 'user_password' => '', 'user_password_validate' => ''], "POST");
        $actual_value = $this->ci->db->query("SELECT user_id FROM users ORDER BY user_id DESC LIMIT 1")->row_array()['user_id'];
        run_test($this->controller, $action, $test_name, $test_aspect, $expected_value, $actual_value);
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
        $response = $this->http_client->response($this->controller, $action, ['user_name' => 'christian', 'user_mail' => '123gmai.com', 'user_username' => 'Malakani', 'user_password' => '123', 'user_password_validate' => '123'], "POST");
        $actual_value = $response->getStatusCode() . ' ' . $response->getReasonPhrase();
        run_test($this->controller, $action, $test_name, $test_aspect, $expected_value, $actual_value);
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
        $this->http_client->response($this->controller, $action, ['user_name' => 'christian', 'user_mail' => '123gmai.com', 'user_username' => 'Malakani', 'user_password' => '123', 'user_password_validate' => '123'], "POST");
        $actual_value = $this->ci->db->query("SELECT user_id FROM users ORDER BY user_id DESC LIMIT 1")->row_array()['user_id'];
        run_test($this->controller, $action, $test_name, $test_aspect, $expected_value, $actual_value);
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
        $response = $this->http_client->response($this->controller, $action, ['user_name' => 'christian', 'user_mail' => '123@gmai.com', 'user_username' => 'Malakani', 'user_password' => '123', 'user_password_validate' => '12'], "POST");
        $actual_value = $response->getStatusCode() . ' ' . $response->getReasonPhrase();
        run_test($this->controller, $action, $test_name, $test_aspect, $expected_value, $actual_value);
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
        $this->http_client->response($this->controller, $action, ['user_name' => 'christian', 'user_mail' => '123@gmai.com', 'user_username' => 'Malakani', 'user_password' => '123', 'user_password_validate' => '12'], "POST");
        $actual_value = $this->ci->db->query("SELECT user_id FROM users ORDER BY user_id DESC LIMIT 1")->row_array()['user_id'];
        run_test($this->controller, $action, $test_name, $test_aspect, $expected_value, $actual_value);
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
        $response = $this->http_client->response($this->controller, $action, ['user_name' => 'christian', 'user_mail' => '123@gmai.com', 'user_username' => 'Malakani', 'user_password' => '123', 'user_password_validate' => '123'], "POST");
        $actual_value = $response->getStatusCode() . ' ' . $response->getReasonPhrase();
        run_test($this->controller, $action, $test_name, $test_aspect, $expected_value, $actual_value);
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
        $this->http_client->response($this->controller, $action, ['user_name' => 'christian', 'user_mail' => '123@gmai.com', 'user_username' => 'Malakani', 'user_password' => '123', 'user_password_validate' => '123'], "POST");
        $actual_value = $this->ci->db->query("SELECT user_id FROM users ORDER BY user_id DESC LIMIT 1")->row_array()['user_id'];
        run_test($this->controller, $action, $test_name, $test_aspect, $expected_value, $actual_value);
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
        $response = $this->http_client->response($this->controller, $action, ['user_name' => 'chris', 'user_mail' => '123@gmai.com', 'user_username' => 'admin', 'user_password' => '123', 'user_password_validate' => '123', 'g-recaptcha-response' => 'relis_test'], "POST");
        $actual_value = $response->getStatusCode() . ' ' . $response->getReasonPhrase();
        run_test($this->controller, $action, $test_name, $test_aspect, $expected_value, $actual_value);
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
        $this->http_client->response($this->controller, $action, ['user_name' => 'chris', 'user_mail' => '123@gmai.com', 'user_username' => 'admin', 'user_password' => '123', 'user_password_validate' => '123', 'g-recaptcha-response' => 'relis_test'], "POST");
        $actual_value = $this->ci->db->query("SELECT user_id FROM users ORDER BY user_id DESC LIMIT 1")->row_array()['user_id'];
        run_test($this->controller, $action, $test_name, $test_aspect, $expected_value, $actual_value);
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
        $response = $this->http_client->response($this->controller, $action, ['user_name' => $user_name, 'user_mail' => $user_mail, 'user_username' => $user_username, 'user_password' => '123', 'user_password_validate' => '123', 'g-recaptcha-response' => 'relis_test'], "POST");
        $actual_value = $response->getStatusCode() . ' ' . $response->getReasonPhrase();
        run_test($this->controller, $action, $test_name, $test_aspect, $expected_value, $actual_value);

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
        run_test($this->controller, $action, $test_name, $test_aspect, $expected_value, $actual_value);
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
        run_test($this->controller, $action, $test_name, $test_aspect, $expected_value, $actual_value);
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
        $response = $this->http_client->response($this->controller, $action);
        $actual_value = $response->getStatusCode() . ' ' . $response->getReasonPhrase();
        run_test($this->controller, $action, $test_name, $test_aspect, $expected_value, $actual_value);
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
        $response = $this->http_client->response($this->controller, $action, ['user_id' => $this->user['user_id'], 'validation_code' => ''], "POST");
        $actual_value = $response->getStatusCode() . ' ' . $response->getReasonPhrase();
        run_test($this->controller, $action, $test_name, $test_aspect, $expected_value, $actual_value);
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
        $this->http_client->response($this->controller, $action, ['user_id' => $this->user['user_id'], 'validation_code' => ''], "POST");
        $actual_value = $this->ci->db->query("Select user_state from users WHERE user_id = '" . $this->user['user_id'] . "'")->row_array()['user_state'];
        run_test($this->controller, $action, $test_name, $test_aspect, $expected_value, $actual_value);
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
        $this->http_client->response($this->controller, $action, ['user_id' => $this->user['user_id'], 'validation_code' => ''], "POST");
        $actual_value = $this->ci->db->query("Select user_creation_active from user_creation WHERE creation_user_id = " . $this->user['user_id'] . "")->row_array()['user_creation_active'];
        run_test($this->controller, $action, $test_name, $test_aspect, $expected_value, $actual_value);
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
        $response = $this->http_client->response($this->controller, $action, ['user_id' => $this->user['user_id'], 'validation_code' => '1234'], "POST");
        $actual_value = $response->getStatusCode() . ' ' . $response->getReasonPhrase();
        run_test($this->controller, $action, $test_name, $test_aspect, $expected_value, $actual_value);
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
        $this->http_client->response($this->controller, $action, ['user_id' => $this->user['user_id'], 'validation_code' => '1234'], "POST");
        $actual_value = $this->ci->db->query("Select user_state from users WHERE user_id = '" . $this->user['user_id'] . "'")->row_array()['user_state'];
        run_test($this->controller, $action, $test_name, $test_aspect, $expected_value, $actual_value);
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
        $this->http_client->response($this->controller, $action, ['user_id' => $this->user['user_id'], 'validation_code' => '1234'], "POST");
        $actual_value = $this->ci->db->query("Select user_creation_active from user_creation WHERE creation_user_id = " . $this->user['user_id'] . "")->row_array()['user_creation_active'];
        run_test($this->controller, $action, $test_name, $test_aspect, $expected_value, $actual_value);
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
        $response = $this->http_client->response($this->controller, $action, ['user_id' => $this->user['user_id'], 'validation_code' => $this->user_confirmation['confirmation_code']], "POST");
        $actual_value = $response->getStatusCode() . ' ' . $response->getReasonPhrase();
        run_test($this->controller, $action, $test_name, $test_aspect, $expected_value, $actual_value);
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
        run_test($this->controller, $action, $test_name, $test_aspect, $expected_value, $actual_value);
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
        run_test($this->controller, $action, $test_name, $test_aspect, $expected_value, $actual_value);
    }

    /*
     * Controller : User
     * Action : login
     * Description : This test verifies the behavior of the 'login' action (go to login form page) when a user is not already logged in (there is no open user session).
     * Scenario : When a user is not logged in and clicks the 'Go to ReLiS' link (go to login form page), the user should be directed to the 'login' HTML form page.
     * Expected HTTP Response Code : 200 (indicating a successful response from the server).
     */
    private function testLoginPageURL_httpResponseCode()
    {
        $action = "login";
        $test_name = "Go to login form page when user is not already logged in";
        $test_aspect = "Http response code";
        $expected_value = http_code()[200];
        $response = $this->http_client->response($this->controller, $action);
        $actual_value = $response->getStatusCode() . ' ' . $response->getReasonPhrase();
        run_test($this->controller, $action, $test_name, $test_aspect, $expected_value, $actual_value);
    }

    /*
     * Controller : User
     * Action : login
     * Description : This test verifies the behavior of the 'login' action (go to login form page) when a user is already logged in (there is an open user session).
     * Scenario : When a user is already logged in and clicks the 'Go to ReLiS' link (go to login form page), the user should be redirected to the 'home/index' url to be automatically logged in.
     * Expected HTTP Response Code : 307 (indicating a temporary Redirect code)
     */
    private function testLoginPageURL_userAlreadyConnected_httpResponseCode()
    {
        $action = "login";
        $test_name = "Go to login form page when user is already logged in";
        $test_aspect = "Http response code";
        $expected_value = http_code()[307];
        // Login
        $this->http_client->response($this->controller, "check_form", ['user_username' => 'admin', 'user_password' => '123'], "POST");
        // Navigate to login form page
        $response = $this->http_client->response($this->controller, $action);
        $actual_value = $response->getStatusCode() . ' ' . $response->getReasonPhrase();
        run_test($this->controller, $action, $test_name, $test_aspect, $expected_value, $actual_value);
        //unset authentication cookie
        $this->http_client->unsetCookie('relis_session');
    }

    /*
     * Controller : User
     * Action : login
     * Description : This test verifies the behavior of the 'login' action (go to login form page) when a user is already logged in (there is an open user session).
     * Scenario : When a user is already logged in and clicks the 'Go to ReLiS' link (go to login form page), the user should be redirected to the 'home/index' url to be automatically logged in.
     * Expected URL : home (The expected URL after the user's action)
     */
    private function testLoginPageURL_userAlreadyConnected_redirectedURL()
    {
        $action = "login";
        $test_name = "Go to login form page when user is already logged in";
        $test_aspect = "Redirected URL";
        $expected_value = 'home';
        //Login
        $this->http_client->response($this->controller, "check_form", ['user_username' => 'admin', 'user_password' => '123'], "POST");
        // Navigate to login form page
        $response = $this->http_client->response($this->controller, $action);
        preg_match('/8083\/(.*?)(\.html)?$/', $response->getHeaders()['Location'][0], $matches);
        $url = $matches[1];
        $actual_value = $url;
        run_test($this->controller, $action, $test_name, $test_aspect, $expected_value, $actual_value);
        //unset authentication cookie
        $this->http_client->unsetCookie('relis_session');
    }

    /*
     * Controller : User
     * Action : check_form
     * Description : Submit login form while all the form fields are empty.
     * Scenario : When the user submit the login form, the user should not be logged in
     * Expected HTTP Response Code : 200
     */
    private function testsubmitLogin_allFieldsEmpty_httpResponseCode()
    {
        //unset authentication cookie
        $this->http_client->unsetCookie('relis_session');
        $action = "check_form";
        $test_name = "Submit login form while all the form fields are empty";
        $test_aspect = "HTTP response code";
        $expected_value = http_code()[200];
        $response = $this->http_client->response($this->controller, $action, ['user_username' => '', 'user_password' => ''], 'POST');
        $actual_value = $response->getStatusCode() . ' ' . $response->getReasonPhrase();
        run_test($this->controller, $action, $test_name, $test_aspect, $expected_value, $actual_value);
    }

    /*
     * Controller : User
     * Action : check_form
     * Description : Submit login form while all the form fields are empty.
     * Scenario : When the user submit the login form, the user should not be logged in
     * Expected userdata(user_id) session: Null (the authentication session must remain null before and after login attempt)
     */
    private function testsubmitLogin_allFieldsEmpty_userSessionCookie()
    {
        //unset authentication cookie
        $this->http_client->unsetCookie('relis_session');
        $this->ci->session->set_userdata('user_id', 'Null');
        $action = "check_form";
        $test_name = "Submit login form while all the form fields are empty";
        $test_aspect = "User session data";
        $this->http_client->getUserdate($this->http_client->getCookieValue("relis_session"));
        $expected_value = $this->ci->session->userdata('user_id');
        $this->http_client->response($this->controller, $action, ['user_username' => '', 'user_password' => ''], "POST");
        $this->http_client->getUserdate($this->http_client->getCookieValue("relis_session"));
        $actual_value = $this->ci->session->userdata('user_id');
        run_test($this->controller, $action, $test_name, $test_aspect, $expected_value, $actual_value);
    }

    /*
     * Controller : User
     * Action : check_form
     * Description : Submit login form with empty username field and filled password field.
     * Scenario : When the user submit the login form, the user should not be logged in
     * Expected userdata(user_id) session: Null (the authentication session must remain null before and after login attempt)
     */
    private function testsubmitLogin_EmptyUsername_userSessionCookie()
    {
        //unset authentication cookie
        $this->http_client->unsetCookie('relis_session');
        $this->ci->session->set_userdata('user_id', 'Null');
        $action = "check_form";
        $test_name = "Submit login form with empty username field and filled password field";
        $test_aspect = "User session data";
        $this->http_client->getUserdate($this->http_client->getCookieValue("relis_session"));
        $expected_value = $this->ci->session->userdata('user_id');
        $this->http_client->response($this->controller, $action, ['user_username' => '', 'user_password' => '123'], "POST");
        $this->http_client->getUserdate($this->http_client->getCookieValue("relis_session"));
        $actual_value = $this->ci->session->userdata('user_id');
        run_test($this->controller, $action, $test_name, $test_aspect, $expected_value, $actual_value);
    }

    /*
     * Controller : User
     * Action : check_form
     * Description : Submit login form with filled username field and empty password field.
     * Scenario : When the user submit the login form, the user should not be logged in
     * Expected userdata(user_id) session: Null (the authentication session must remain null before and after login attempt)
     */
    private function submitLogin_EmptyPassword_userSessionCookie()
    {
        //unset authentication cookie
        $this->http_client->unsetCookie('relis_session');
        $this->ci->session->set_userdata('user_id', 'Null');
        $action = "check_form";
        $test_name = "Submit login form with filled username field and empty password field";
        $test_aspect = "User session data";
        $this->http_client->getUserdate($this->http_client->getCookieValue("relis_session"));
        $expected_value = $this->ci->session->userdata('user_id');
        $this->http_client->response($this->controller, $action, ['user_username' => 'admin', 'user_password' => ''], "POST");
        $this->http_client->getUserdate($this->http_client->getCookieValue("relis_session"));
        $actual_value = $this->ci->session->userdata('user_id');
        run_test($this->controller, $action, $test_name, $test_aspect, $expected_value, $actual_value);
    }

    /*
     * Controller : User
     * Action : check_form
     * Description : Submit login form with correct username and wrong password.
     * Scenario : When the user submit the login form, the user should not be logged in
     * Expected userdata(user_id) session: Null (the authentication session must remain null before and after login attempt)
     */
    private function submitLogin_wrongPassword_userSessionCookie()
    {
        //unset authentication cookie
        $this->http_client->unsetCookie('relis_session');
        $this->ci->session->set_userdata('user_id', 'Null');
        $action = "check_form";
        $test_name = "Submit login form with correct username and wrong password";
        $test_aspect = "User session data";
        $this->http_client->getUserdate($this->http_client->getCookieValue("relis_session"));
        $expected_value = $this->ci->session->userdata('user_id');
        $this->http_client->response($this->controller, $action, ['user_username' => 'admin', 'user_password' => '111'], "POST");
        $this->http_client->getUserdate($this->http_client->getCookieValue("relis_session"));
        $actual_value = $this->ci->session->userdata('user_id');
        run_test($this->controller, $action, $test_name, $test_aspect, $expected_value, $actual_value);
    }

    /*
     * Controller : User
     * Action : check_form
     * Description : Submit login form with wrong username and correct password.
     * Scenario : When the user submit the login form, the user should not be logged in
     * Expected userdata(user_id) session: Null (the authentication session must remain null before and after login attempt)
     */
    private function submitLogin_wrongUsername_userSessionCookie()
    {
        //unset authentication cookie
        $this->http_client->unsetCookie('relis_session');
        $this->ci->session->set_userdata('user_id', 'Null');
        $action = "check_form";
        $test_name = "Submit login form with wrong username and correct password";
        $test_aspect = "User session data";
        $this->http_client->getUserdate($this->http_client->getCookieValue("relis_session"));
        $expected_value = $this->ci->session->userdata('user_id');
        $this->http_client->response($this->controller, $action, ['user_username' => 'aaa', 'user_password' => '123'], "POST");
        $this->http_client->getUserdate($this->http_client->getCookieValue("relis_session"));
        $actual_value = $this->ci->session->userdata('user_id');
        run_test($this->controller, $action, $test_name, $test_aspect, $expected_value, $actual_value);
    }

    /*
     * Controller : User
     * Action : check_form
     * Description : Submit login form with a non validated account.
     * Scenario : When the user submit the login form, the user should not be logged in
     * Expected userdata(user_id) session: Null (the authentication session must remain null before and after login attempt)
     */
    private function submitLogin_nonValidatedAccount_userSessionCookie()
    {
        //unset authentication cookie
        $this->http_client->unsetCookie('relis_session');
        $this->ci->session->set_userdata('user_id', 'Null');
        $action = "check_form";
        $test_name = "Submit login form with a non validated account";
        $test_aspect = "User session data";

        $username = "jackson";
        //Create new user
        $this->http_client->response($this->controller, "check_create_user", ['user_name' => "john", 'user_mail' => "abc@gmail.com", 'user_username' => $username, 'user_password' => '123', 'user_password_validate' => '123', 'g-recaptcha-response' => 'relis_test'], "POST");
        $this->http_client->getUserdate($this->http_client->getCookieValue("relis_session"));
        $expected_value = $this->ci->session->userdata('user_id');
        $this->http_client->response($this->controller, $action, ['user_username' => $username, 'user_password' => '123'], "POST");
        $this->http_client->getUserdate($this->http_client->getCookieValue("relis_session"));
        $actual_value = $this->ci->session->userdata('user_id');
        run_test($this->controller, $action, $test_name, $test_aspect, $expected_value, $actual_value);
    }

    /*
     * Controller : User
     * Action : check_form
     * Description : Submit login form with correct username and correct password.
     * Scenario : When the user submit the login form, the user should not be logged in
     * Expected userdata(user_id) session: user ID of the login user
     */
    private function submitLogin_correctUsernameAndPassword_userSessionCookie()
    {
        //unset authentication cookie
        $this->http_client->unsetCookie('relis_session');
        $this->ci->session->set_userdata('user_id', 'Null');
        $action = "check_form";
        $test_name = "Submit login form with correct username and correct password";
        $test_aspect = "User ID session data";
        $username = "admin";
        $password = "123";
        $expected_value = $this->ci->db->query("SELECT user_id FROM users WHERE user_username = '$username'")->row_array()['user_id'];
        $this->http_client->response($this->controller, $action, ['user_username' => $username, 'user_password' => $password], "POST");
        $this->http_client->getUserdate($this->http_client->getCookieValue("relis_session"));
        $actual_value = $this->ci->session->userdata('user_id');
        run_test($this->controller, $action, $test_name, $test_aspect, $expected_value, $actual_value);
    }

    /*
     * Controller : User
     * Action : demo_user
     * Description : Login as demo user
     * Scenario : When the user click on "demo user" link the user should login as demo user
     * Expected userdata(user_id) session: 2 (user_id of demo user)
     */
    private function loginAsDemoUser()
    {
        //unset authentication cookie
        $this->http_client->unsetCookie('relis_session');
        $this->ci->session->set_userdata('user_id', 'Null');
        $action = "demo_user";
        $test_name = "Login as demo user";
        $test_aspect = "User ID session data";
        $username = "demo";
        $expected_value = $this->ci->db->query("SELECT user_id FROM users WHERE user_username = '$username'")->row_array()['user_id'];
        $this->http_client->response($this->controller, $action);
        $this->http_client->getUserdate($this->http_client->getCookieValue("relis_session"));
        $actual_value = $this->ci->session->userdata('user_id');
        run_test($this->controller, $action, $test_name, $test_aspect, $expected_value, $actual_value);
    }

    /*
     * Controller : User
     * Action : discon
     * Description : logout
     * Expected userdata(user_id) session: 0
     */
    private function logout()
    {
        //unset authentication cookie
        $this->http_client->unsetCookie('relis_session');
        $this->ci->session->set_userdata('user_id', 0);
        $action = "discon";
        $test_name = "Logout";
        $test_aspect = "User ID session data";
        $expected_value = 0;
        //Login first
        $this->http_client->response($this->controller, "check_form", ['user_username' => 'admin', 'user_password' => '123'], "POST");

        //logout
        $this->http_client->response($this->controller, $action);
        $actual_value = $this->ci->session->userdata('user_id');
        run_test($this->controller, $action, $test_name, $test_aspect, $expected_value, $actual_value);
    }
}