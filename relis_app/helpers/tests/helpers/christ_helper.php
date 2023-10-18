<?php
/////////////////////////////////////// NEW ///////////////////////////////////

require 'vendor/autoload.php';
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class Http_client
{
    private $ci;
    private $client;
    private $cookies;

    function __construct()
    {
        $this->ci = get_instance();
        $this->client = new Client(['base_uri' => 'http://host.docker.internal:8083/', 'cookies' => true]);
        $this->cookies = $this->client->getConfig('cookies');
    }

    function response($controller, $action, $data = [], $http_method = "GET")
    {
        try {
            if ($http_method == "GET") {
                return $this->client->get($controller . '/' . $action, ['allow_redirects' => false]);
            } elseif ($http_method == "POST") {
                return $this->client->post($controller . '/' . $action, ['form_params' => $data, 'allow_redirects' => false]);
            }
        } catch (RequestException $e) {
            // Return the exception as part of the response
            return $e->getResponse();
        }
    }

    function getCookieValue($cookieName)
    {
        $cookie = $this->cookies->getCookieByName($cookieName);

        if (!$cookie == null) {
            return $cookie->getValue();
        } else {
            return null;
        }
    }

    function unsetCookie($cookieName)
    {
        $this->cookies->clear(null, null, $cookieName);
    }

    function getUserdate($session_id)
    {
        $file = 'cside/sessions/relis_session' . $session_id;

        if (file_exists($file)) {
            $session_file_contents = file_get_contents($file);
            session_decode($session_file_contents);
        } else {
            return null;
        }
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