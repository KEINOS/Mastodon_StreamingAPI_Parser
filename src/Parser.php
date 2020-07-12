<?php

declare(strict_types=1);

namespace KEINOS\MSTDN_TOOLS;

final class Parser extends ParserProtectedMethods implements ParserInterface, ParserConstants
{
    public function parse(string $line)
    {
        if ($this->isSkippable($line)) {
            return false; // Return false to skip further process
        }

        if ($this->isFlagUpEventDelete()) {
            if (self::isDataTootId($line)) {
                $id_toot = trim(str_replace('data:', '', $line));

                $this->resetFlags();
                $this->clearBuffer();

                $result = json_encode([
                    'event'   => 'delete',
                    'payload' => "${id_toot}"
                ]);
                return $result;
            }
        }

        if ($this->ifFlagUpEventUpdate()) {
            $payload_array = $this->bufferPayloadUpdate($line);

            if (false !== $payload_array) {
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
