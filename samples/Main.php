<?php

/**
 * Main script.
 *
 * Overly-Cautious Development of Hello World!.
 *
 * @standard PSR2
 */

declare(strict_types=1);

namespace KEINOS\MSTDN_TOOLS;

require_once __DIR__ . '/../vendor/autoload.php';

// Endpoints
const URL_API_STREAM_PUBLIC = '/api/v1/streaming/public';
const URL_API_STREAM_LOCAL  = '/api/v1/streaming/public/local';
const CRLF = "\r\n";

// Open socket to the Mastodon server
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
$endpoint   = URL_API_STREAM_PUBLIC; // For LTL: URL_API_STREAM_LOCAL
$host       = 'qiitadon.com'; //streaming.qiitadon.con でない
$user_agent = 'qithub-bot';
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
