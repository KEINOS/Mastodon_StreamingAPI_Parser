<?php

declare(strict_types=1);

namespace KEINOS\Tests;

use KEINOS\MSTDN_TOOLS\Parser;

final class Issue_1_Test extends TestCase
{
    public function testBadByteLengthInput()
    {
        $sample  = new Parser();

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
            $actual = $sample->parse(strval($line));
            if (false !== $actual) {
                break;
            }
        }
        $expect = '{"event":"update","payload":{"sample":"<br><a href=\"https:\/\/www.foo.com\/\">bar<\/a>"}}';
        $this->assertSame($expect, $actual);
    }
}
