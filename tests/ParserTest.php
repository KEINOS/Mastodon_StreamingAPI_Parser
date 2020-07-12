<?php

declare(strict_types=1);

namespace KEINOS\Tests;

use KEINOS\MSTDN_TOOLS\Parser;

final class ClassParserTest extends TestCase
{
    public function testStringInput()
    {
        $sample  = new Parser();

        $event   = 'update';
        $payload = json_encode(['foo' => 'bar']);
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

        $actual = json_decode($result, true);
        $expect = [
            'event' => $event,
            'payload' => json_decode($payload, true)
        ];
        $this->assertSame($expect, $actual);
    }

    public function testArrayInput()
    {
        $sample = new Parser();

        $this->expectException(\TypeError::class);
        $actual = $sample->parse(array());
    }
}
