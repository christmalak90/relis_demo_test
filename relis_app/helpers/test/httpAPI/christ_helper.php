<?php
/////////////////////////////////////// NEW ///////////////////////////////////

class UnitTest
{
    private $ci;
    private $cookieFile;

    function __construct()
    {
        $this->ci = get_instance();
        $this->cookieFile = 'relis_app/helpers/test/httpAPI/cookies.txt';
    }

    function http_GET($endpoint, $headers = [])
    {
        return $this->request('GET', $endpoint, [], $headers);
    }

    function http_POST($endpoint, $data = [], $headers = [])
    {
        return $this->request('POST', $endpoint, $data, $headers);
    }

    function http_PUT($endpoint, $data = [], $headers = [])
    {
        return $this->request('PUT', $endpoint, $data, $headers);
    }

    function http_DELETE($endpoint, $headers = [])
    {
        return $this->request('DELETE', $endpoint, [], $headers);
    }

    function request($method, $endpoint, $data = [], $headers = [])
    {
        // Construct the full URL by appending the endpoint to the base URL
        $url = 'http://host.docker.internal:8083/' . $endpoint;

        // Initialize cURL session
        $curl = curl_init($url);

        // Set cURL options
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true); // Follow redirects
        curl_setopt($curl, CURLOPT_MAXREDIRS, 1); // Maximum number of redirects to follow
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        // Set the cookie file to save and send cookies
        curl_setopt($curl, CURLOPT_COOKIEJAR, $this->cookieFile);
        curl_setopt($curl, CURLOPT_COOKIEFILE, $this->cookieFile);

        if ($method === 'POST' || $method === 'PUT') {
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        }

        // Execute the cURL request and capture the response
        $response = curl_exec($curl);

        // Get the HTTP header size
        $headerSize = curl_getinfo($curl, CURLINFO_HEADER_SIZE);

        // Separate the header and content from the response
        $header = substr($response, 0, $headerSize);
        $content = substr($response, $headerSize);

        // Get additional information about the request
        $urlInfo = curl_getinfo($curl);

        // Close the cURL session
        curl_close($curl);

        // Return the response data as an associative array
        return [
            'status_code' => http_code()[$urlInfo['http_code']],
            // HTTP headers
            'headers' => $header,
            // Response content
            'content' => $content,
            'url' => $urlInfo['url'],
        ];
    }

    public function unset_cookie($cookie_name) {
        // Read the content of the cookie file
        $cookieContent = file_get_contents($this->cookieFile);
    
        // Remove cookie entry
        $cookieContent = preg_replace('/\b' . $cookie_name . '\b.*\R?/', '', $cookieContent);
    
        // Write the updated content back to the cookie file
        file_put_contents($this->cookieFile, $cookieContent);
    }

    function response($controller, $action, $data = [], $http_method = "GET")
    {
        if ($http_method == "GET") {
            return $this->http_GET($controller . '/' . $action, $data);
        } elseif ($http_method == "POST") {
            return $this->http_POST($controller . '/' . $action, $data);
        }
    }

    function run_test($controller, $action, $test_name, $test_aspect, $expected_values, $actual_values)
    {
        $this->ci->unit->run($actual_values, $expected_values, $test_name, $test_aspect, $controller, $action);
    }
}

function http_code()
{
    return array(
        100 => '100 Continue',
        101 => '101 Switching Protocols',
        102 => '102 Processing',
        103 => '103 Early Hints',
        200 => '200 OK',
        201 => '201 Created',
        202 => '202 Accepted',
        203 => '203 Non-Authoritative Information',
        204 => '204 No Content',
        205 => '205 Reset Content',
        206 => '206 Partial Content',
        207 => '207 Multi-Status',
        208 => '208 Already Reported',
        226 => '226 IM Used',
        300 => '300 Multiple Choices',
        301 => '301 Moved Permanently',
        302 => '302 Found',
        303 => '303 See Other',
        304 => '304 Not Modified',
        305 => '305 Use Proxy',
        307 => '307 Temporary Redirect',
        308 => '308 Permanent Redirect',
        400 => '400 Bad Request',
        401 => '401 Unauthorized',
        402 => '402 Payment Required',
        403 => '403 Forbidden',
        404 => '404 Not Found',
        405 => '405 Method Not Allowed',
        406 => '406 Not Acceptable',
        407 => '407 Proxy Authentication Required',
        408 => '408 Request Timeout',
        409 => '409 Conflict',
        410 => '410 Gone',
        411 => '411 Length Required',
        412 => '412 Precondition Failed',
        413 => '413 Payload Too Large',
        414 => '414 URI Too Long',
        415 => '415 Unsupported Media Type',
        416 => '416 Range Not Satisfiable',
        417 => '417 Expectation Failed',
        418 => "418 I'm a Teapot",
        421 => '421 Misdirected Request',
        422 => '422 Unprocessable Entity',
        423 => '423 Locked',
        424 => '424 Failed Dependency',
        425 => '425 Too Early',
        426 => '426 Upgrade Required',
        428 => '428 Precondition Required',
        429 => '429 Too Many Requests',
        431 => '431 Request Header Fields Too Large',
        451 => '451 Unavailable For Legal Reasons',
        500 => '500 Internal Server Error',
        501 => '501 Not Implemented',
        502 => '502 Bad Gateway',
        503 => '503 Service Unavailable',
        504 => '504 Gateway Timeout',
        505 => '505 HTTP Version Not Supported',
        506 => '506 Variant Also Negotiates',
        507 => '507 Insufficient Storage',
        508 => '508 Loop Detected',
        510 => '510 Not Extended',
        511 => '511 Network Authentication Required'
    );
}