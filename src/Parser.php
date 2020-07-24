<?php

declare(strict_types=1);

namespace KEINOS\MSTDN_TOOLS\Parser;

final class Parser extends ParserProtectedMethods implements ParserInterface
{
    public function parse(string $line)
    {
        if ($this->isSkippable($line)) {
            return false; // Return false to skip further process
        }

        if ($this->isFlagUpEventDelete() && self::isDataTootId($line)) {
            $id_toot = self::extractDataFromString($line);

            $this->resetFlags();
            $this->clearBuffer();

            $result = json_encode([
                    'event'   => 'delete',
                    'payload' => [
                        'id' => "${id_toot}"
                    ],
            ]);
            return $result;
        }

        if ($this->isFlagUpEventUpdate()) {
            if (false !== $payload_array = $this->bufferPayloadUpdate($line)) {
                $this->resetFlags();
                $this->clearBuffer();

                $result = json_encode([
                    'event'   => 'update',
                    'payload' => $payload_array
                ]);
                return $result;
            }
        }

        return false;
    }
}
