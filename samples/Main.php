<?php

/**
 * Sample of Receiving server-sent messages from Mastodon API as JSON objects.
 */

declare(strict_types=1);

namespace KEINOS\MSTDN_TOOLS;

require_once __DIR__ . '/../vendor/autoload.php';

// Endpoints
const URL_API_STREAM_PUBLIC = '/api/v1/streaming/public';
const URL_API_STREAM_LOCAL  = '/api/v1/streaming/public/local';
const CRLF = "\r\n";

// Open socket to the Mastodon server's streaming API port. You can get these
// info by accessing to your Mastodon API's  `instance` endpoint.
//   Endpoint: /api/v1/instance
//   Ex: https://qiitadon.com/api/v1/instance
$hostname = 'ssl://streaming.qiitadon.com';
$port     = 4000;
$timeout  = 5; //sec
$errno    = '';
$errstr   = '';
$fp = fsockopen($hostname, $port, $errno, $errstr, $timeout);

if (! $fp) {
    die("Error: {$errstr} ({$errno})");
}

// Prepare request header
$method     = 'GET';
$endpoint   = URL_API_STREAM_PUBLIC; // For LTL use: URL_API_STREAM_LOCAL
$host       = 'qiitadon.com'; // Your instance's host name
$user_agent = 'qithub-bot';   // Your app name
$req = [
    "{$method} {$endpoint} HTTP/1.1",
    "Host: {$host}",
    "User-Agent: {$user_agent}",
];
$req = implode($req, CRLF) . CRLF . CRLF; // The request must contain 2 extra blank lines

// Prepare the streaming parser
$parser = new \KEINOS\MSTDN_TOOLS\Parser();

// Send GET request
fwrite($fp, $req);

// Start reading stream
while (! feof($fp)) {
    $read = fgets($fp);
    echo 'Received: ' . trim($read) . PHP_EOL;

    // Buffer each line
    $result = $parser->parse($read);
    if (false === $result) {
        continue;
    }

    // Echo the parsed event and payload
    echo PHP_EOL;
    echo 'Parsed  : ' .  $result . PHP_EOL . PHP_EOL;
}

fclose($fp);
