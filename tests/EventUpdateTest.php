<?php

declare(strict_types=1);

namespace KEINOS\Tests;

use KEINOS\MSTDN_TOOLS\Parser;

final class EventUpdateTest extends TestCase
{
    public function testArrayInput()
    {
        $sample = new Parser();

        $this->expectException(\TypeError::class);
        $actual = $sample->parse(array());
    }

    public function testStringInput()
    {
        $sample  = new Parser();

        $event     = 'update';
        $payload  = '{"foo":"bar","hoge":"fuga","buz":{"piyo":"piyopiyo"}}';
        $length   = strlen('data: ' . $payload);
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

    public function testNotDataStringInput()
    {
        $sample  = new Parser();

        $event     = 'update';
        $payload  = '{"foo":"bar","hoge":"fuga","buz":{"piyo":"piyo piyo"}}';
        $length   = strlen($payload);
        $data_stream = [
            "event: ${event}",
            $length,
            "${payload}"
        ];

        // Buffer stream
        foreach ($data_stream as $line) {
            $result = $sample->parse(strval($line));
            if (! empty($result)) {
                $this->fail('Un-willing data returned. Data: ' . $result);
            }
        }
        // Payload without "data:" pre-fix should return false
        $this->assertFalse($result);
    }

    public function testBadByteLengthInput()
    {
        $sample  = new Parser();

        $event     = 'update';
        $payload  = '{"foo":"bar","hoge":"fuga","buz":{"piyo":"piyo piyo"}}';
        $length   = strlen($payload);
        $data_stream = [
            "event: ${event}",
            "gggg",
            "${payload}"
        ];

        // Buffer stream
        foreach ($data_stream as $line) {
            $result = $sample->parse(strval($line));
            if (! empty($result)) {
                $this->fail('Un-willing data returned. Data: ' . $result);
            }
        }
        // Payload without "data:" pre-fix should return false
        $this->assertFalse($result);
    }

    public function testChunkedStringInput()
    {
        $sample  = new Parser();

        $event    = 'update';
        $payload  = '{"foo":"bar","hoge":"fuga","buz":{"piyo":"piyo piyo"}}';
        $payloads = str_split($payload, (intdiv(strlen($payload), 2)) + 1); // Chunk into 2 peases
        $length   = strlen('data: ' . $payloads[0]);
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

    public function testBrokenChunkedStringInput()
    {
        $sample  = new Parser();

        $event     = 'update';
        $payload  = '{"foo":"bar","hoge":"fuga","buz":{"piyo":"piyo piyo}}'; // Missing quote
        $payloads = str_split($payload, (intdiv(strlen($payload), 2)) + 1);
        $length   = strlen('data: ' . $payloads[0]);
        $data_stream = [
            "event: ${event}",
            $length,
            "data: ${payload}"
        ];

        // Buffer stream
        foreach ($data_stream as $line) {
            $result = $sample->parse(strval($line));
            if (! empty($result)) {
                $this->fail('Un-willing data returned. Data: ' . $result);
            }
        }
        $this->assertFalse($result);
    }

    public function testUncompletedInput()
    {
        $sample  = new Parser();

        $event   = 'update';
        $payload = json_encode(['foo' => 'bar']);
        $length  = strlen('data: ' . $payload);
        $data_stream = [
            "event: delete",
            $length,
            "event: update", // Delete event should be reset here
            $length,
            $length,
        ];

        // Buffer stream
        foreach ($data_stream as $line) {
            $result = $sample->parse(strval($line));
            if (! empty($result)) {
                $this->fail('Un-willing data returned. Data: ' . $result);
            }
        }
        $this->assertFalse($result);
    }
}
