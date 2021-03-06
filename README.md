[![](https://travis-ci.org/KEINOS/Mastodon_StreamingAPI_Parser.svg?branch=master)](https://travis-ci.org/KEINOS/Mastodon_StreamingAPI_Parser "View Build Status on Travis")
[![](https://img.shields.io/coveralls/github/KEINOS/Mastodon_StreamingAPI_Parser)](https://coveralls.io/github/KEINOS/Mastodon_StreamingAPI_Parser?branch=master "Code Coverage on COVERALLS")
[![](https://img.shields.io/scrutinizer/quality/g/KEINOS/Mastodon_StreamingAPI_Parser/master)](https://scrutinizer-ci.com/g/KEINOS/Mastodon_StreamingAPI_Parser/?branch=master "Code quality in Scrutinizer")
[![](https://img.shields.io/packagist/php-v/keinos/mastodon-streaming-api-parser)](https://github.com/KEINOS/Mastodon_StreamingAPI_Parser/blob/master/.travis.yml "Version Support")

# Server-Sent Events parser of Mastodon Streaming API in PHP

This class simply parses the received lines from [server-sent events](https://developer.mozilla.org/en-US/docs/Web/API/Server-sent_events/Using_server-sent_events) of the [Mastodon Streaming API](https://docs.joinmastodon.org/methods/timelines/streaming/) to JSON object string.

The below lines (server-sent event messages) will be parsed in JSON as below.

```text
5b1
event: update
data: {"id":"10000 ... visible\">0FS8QET2TT</span></a><br><a hr
350
ef=\"https://www ... ts":[],"mentions":[],"tags":[],"emojis":[]}
```

↓

```php
use \KEINOS\MSTDN_TOOLS\Parser\Parser;

$parser = new Parser();

while (! feof($stream)) {
    $line = fgets($stream);
    $json = $parser->parse($line);
    if (false === $json) {
        continue;
    }
    echo $json . PHP_EOL;
}
```

↓

```json
{"event":"update","payload":{"id":"10000 ... visible\">0FS8QET2TT<\/span><\/a><br><a href=\"https:\/\/www .... ts":[],"mentions":[],"tags":[],"emojis":[]}}
```

Use this class if you are receiving the streaming signal directly from Mastodon Streaming API via socket connection, rather than WebSocket which requires an access token when upgrading the protocol.

## Install

```bash
composer require keinos/mastodon-streaming-api-parser
```

## Usage

- Instantiation

    ```php
    $parser = new \KEINOS\MSTDN_TOOLS\Parser\Parser();
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

- Interface: [ParserInterface.php](https://github.com/KEINOS/Mastodon_StreamingAPI_Parser/blob/master/src/interfaces/ParserInterface.php)

### Sample

```php
// Instantiate the parser
$parser = new \KEINOS\MSTDN_TOOLS\Parser\Parser();
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

- Complete sample see: [./samples/Main.php](https://github.com/KEINOS/Mastodon_StreamingAPI_Parser/blob/master/samples/Main.php)

## Specifications/Info

- PHP: ^7.1 || ^8.0
- Available [event types](https://docs.joinmastodon.org/methods/timelines/streaming/#event-types-a-idevent-typesa) to detect: `update` and `delete`
- Source Code: [Mastodon_StreamingAPI_Parser](https://github.com/KEINOS/Mastodon_StreamingAPI_Parser) @ GitHub
- Package release: [keinos/mastodon-streaming-api-parser](https://packagist.org/packages/keinos/mastodon-streaming-api-parser) @ Packagist

## Contribution

- Issues
  - Throw an issue at: https://github.com/KEINOS/Mastodon_StreamingAPI_Parser/issues
  - If the issue is a bug, then please provide a simple example that are reproducible.
  - If the issue is a feature request, then also provide a simple example with the results you wish, so we can create a test before implementing your feature.
- PR (Pull Request)
  - Please **PR to the `padawan` branch**. After the CI tests/reviews, then merged to `master` branch it will ;-)
  - For more see: [CONTRIBUTE.md](https://github.com/KEINOS/Mastodon_StreamingAPI_Parser/blob/master/CONTRIBUTE.md)
- Rules
  - Be nice, be happy and stay safe.
