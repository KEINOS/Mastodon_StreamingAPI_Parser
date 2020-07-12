[![](https://travis-ci.org/KEINOS/Mastodon_StreamingAPI_Parser.svg?branch=master)](https://travis-ci.org/KEINOS/Mastodon_StreamingAPI_Parser "View Build Status on Travis")
[![](https://coveralls.io/repos/github/Mastodon_StreamingAPI_Parser/badge.svg)](https://coveralls.io/github/Mastodon_StreamingAPI_Parser "Coverage Status")
[![](https://img.shields.io/scrutinizer/quality/g/Mastodon_StreamingAPI_Parser/master)](https://scrutinizer-ci.com/g/KEINOS/Mastodon_StreamingAPI_Parser/master "Scrutinizer code quality")
[![](https://img.shields.io/packagist/php-v/keinos/mastodon-streaming-api-parser)](https://github.com/KEINOS/Mastodon_StreamingAPI_Parser/blob/master/.travis.yml "Version Support")

# Server-Sent Events parser of Mastodon Streaming API in PHP

This class simply parses the received lines from [server-sent events](https://developer.mozilla.org/en-US/docs/Web/API/Server-sent_events/Using_server-sent_events) of the [Mastodon Streaming API](https://docs.joinmastodon.org/methods/timelines/streaming/) to JSON object string.

```text
b59
event: update
data: {"id":"104 ... <span cla
274
ss=\"invisible\"> ... ,"mojis":[]}
```

The above lines (server-sent event messages) will be parsed in JSON as below and nothing more.

```json
{"event":"update","payload":{"id":"104 ... <span class=\"invisible\"> ... ,"emojis":[]}}
```

Use this class if you are receiving the streaming signal directly from Mastodon Streaming API via socket connection, rather than WebSocket which requires an access token when upgrading the protocol.

## Usage

- Install (via composer)

    ```bash
    composer require keinos/keinos/mastodon-streaming-api-parser
    ```

- Instantiate

    ```php
    $parser = new \KEINOS\MSTDN_TOOLS\Parser();
    ```

- Method

    ```php
    /**
     * @param  string $line   Received streaming line.
     * @return bool|string    Returns the data unit in JSON string. Or false if the
     *                        status is "buffering in progress".
     */
    $json = $parser->parse($line);
    ```

- Returned JSON string structure

    ```json
    {
        "event":"[Event name]",
        "payload":"[Data of the event]"
    }
    ```

    - For the "`[Event name]`" and "`[Data of the event]`" see: [ParserInterface.php](./src/interfaces/ParserInterface.php])

- Sample

    ```php
    // Instantiate the parser
    $parser = new \KEINOS\MSTDN_TOOLS\Parser();
    // Open socket
    $fp = fsockopen($hostname, $port, $errno, $errstr, $timeout);
    // Send GET request
    fwrite($fp, $req);
    // Looper
    while (! feof($fp)) {
        // Read the stream
        $read = fgets($fp);
        // Buffer each line until it returns the data
        $json = $parser->parse($read);
        if (false === $json) {
            continue;
        }
        // Do something with the data
        echo $json . PHP_EOL;
    }
    // Close connection
    fclose($fp);

    ```

    - Complete sample see: [./samples/Main.php](./samples/Main.php)

## Specifications

- PHP ^7.1 || ^8.0
- Available [event types](https://docs.joinmastodon.org/methods/timelines/streaming/#event-types-a-idevent-typesa) to detect: `update` and `delete`
