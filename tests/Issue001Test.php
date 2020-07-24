<?php

/**
 * Test for issue: https://github.com/KEINOS/Mastodon_StreamingAPI_Parser/issues/1
 */

declare(strict_types=1);

namespace KEINOS\Tests;

use KEINOS\MSTDN_TOOLS\Parser\Parser;

final class Issue001Test extends TestCase
{
    public function testChunkAfterSpace()
    {
        $obj = new Parser();
        $data_stream = [
            strlen("event: update"),
            "event: update",
            strlen('data: {"sample": "<br><a '),
            'data: {"sample": "<br><a ',
            strlen('href=\"https://www.foo.com/\">bar</a>"}'),
            'href=\"https://www.foo.com/\">bar</a>"}',
        ];

        // Buffer stream
        foreach ($data_stream as $line) {
            $actual = $obj->parse(strval($line));
            if (false !== $actual) {
                break;
            }
        }
        $expect = '{"event":"update","payload":{"sample":"<br><a href=\"https:\/\/www.foo.com\/\">bar<\/a>"}}';
        $this->assertSame($expect, $actual);
    }

    public function testChunkBeforeSpace()
    {
        $obj = new Parser();
        $data_stream = [
            strlen("event: update"),
            "event: update",
            strlen('data: {"sample": "<br><a'),
            'data: {"sample": "<br><a',
            strlen(' href=\"https://www.foo.com/\">bar</a>"}'),
            ' href=\"https://www.foo.com/\">bar</a>"}',
        ];

        // Buffer stream
        foreach ($data_stream as $line) {
            $actual = $obj->parse(strval($line));
            if (false !== $actual) {
                break;
            }
        }
        $expect = '{"event":"update","payload":{"sample":"<br><a href=\"https:\/\/www.foo.com\/\">bar<\/a>"}}';
        $this->assertSame($expect, $actual);
    }
}
