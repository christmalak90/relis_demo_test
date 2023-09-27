<?php
/////////////////////////////////////// NEW ///////////////////////////////////

function http_GET($endpoint, $headers = [])
{
    return request('GET', $endpoint, [], $headers);
}

function http_POST($endpoint, $data = [], $headers = [])
{
    return request('POST', $endpoint, $data, $headers);
}

function http_PUT($endpoint, $data = [], $headers = [])
{
    return request('PUT', $endpoint, $data, $headers);
}

function http_DELETE($endpoint, $headers = [])
{
    return request('DELETE', $endpoint, [], $headers);
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
        'status_code' => $urlInfo['http_code'] . ' ' . status_code_description()[$urlInfo['http_code']],
        // HTTP headers
        'headers' => $header,
        // Response content
        'content' => $content,
        'url' => $urlInfo['url'],
    ];
}

function status_code_description()
{
    return array(
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',
        103 => 'Early Hints',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-Status',
        208 => 'Already Reported',
        226 => 'IM Used',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        307 => 'Temporary Redirect',
        308 => 'Permanent Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Payload Too Large',
        414 => 'URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Range Not Satisfiable',
        417 => 'Expectation Failed',
        418 => "I'm a Teapot",
        421 => 'Misdirected Request',
        422 => 'Unprocessable Entity',
        423 => 'Locked',
        424 => 'Failed Dependency',
        425 => 'Too Early',
        426 => 'Upgrade Required',
        428 => 'Precondition Required',
        429 => 'Too Many Requests',
        431 => 'Request Header Fields Too Large',
        451 => 'Unavailable For Legal Reasons',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        506 => 'Variant Also Negotiates',
        507 => 'Insufficient Storage',
        508 => 'Loop Detected',
        510 => 'Not Extended',
        511 => 'Network Authentication Required'
    );
}

function test_unit($controller, $action, $test_name, $test_aspects = [], $expected_values = array(), $actual_values = array())
{
    $ci = get_instance();

    foreach ($test_aspects as $aspect) {
        $ci->unit->run($actual_values[$aspect], $expected_values[$aspect], $test_name, $aspect, $controller, $action);
    }
}