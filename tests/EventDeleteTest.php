<?php

declare(strict_types=1);

namespace KEINOS\Tests;

use KEINOS\MSTDN_TOOLS\Parser\Parser;

final class EventDeleteTest extends TestCase
{
    public function testRegularInput()
    {
        $sample  = new Parser();

        $event   = 'delete';
        $payload = '12345';
        $length  = strlen('data: ' . $payload);
        $data_stream = [
            "event: ${event}",
            $length,
            "data: ${payload}"
        ];

        // Buffer stream
        foreach ($data_stream as $line) {
            $result = $sample->parse(strval($line));
            if (empty($result)) {
                continue;
            }
        }

        $data_payload = [
            "id" => $payload
        ];

        $actual = json_decode($result, true);
        $expect = [
            'event' => $event,
            'payload' => $data_payload,
        ];
        $this->assertSame($expect, $actual);
    }
}
