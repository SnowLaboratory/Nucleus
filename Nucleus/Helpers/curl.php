<?php

function fetchJson($url)
{
    //  Initiate curl
    $ch = curl_init();
    // Will return the response, if false it print the response
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // Set the url
    curl_setopt($ch, CURLOPT_URL,$url);

    $headers = [
        'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:28.0) Gecko/20100101 Firefox/28.0',
    ];
    
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    // Execute
    $result=curl_exec($ch);
    // Closing
    curl_close($ch);

    return json_decode($result, true);
}