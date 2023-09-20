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
        'status_code' => $urlInfo['http_code'] . ' ' . stautus_code_description()[$urlInfo['http_code']],
        // HTTP headers
        'headers' => $header,
        // Response content
        'content' => $content,
        'url' => $urlInfo['url'],
    ];
}