<?php

declare(strict_types=1);

namespace KEINOS\MSTDN_TOOLS;

class ParserProtectedMethods extends ParserStaticMethods
{
    /** @var bool */
    protected $flagByteLen;
    /** @var bool */
    protected $flagEventDelete;
    /** @var bool */
    protected $flagEventUpdate;
    /** @var string */
    protected $buffer;

    public function __construct()
    {
        $this->resetFlags();
        $this->clearBuffer();
    }

    /**
     * Buffers chunked payloads until accomplishes the data.
     *
     * @param  string $line
     * @return bool|array<string,mixed>
     */
    protected function bufferPayloadUpdate(string $line)
    {
        if (self::isDataBeginPayload($line)) {
            $this->buffer = trim($line);
        } else {
            $this->buffer .= trim($line);
        }

        if ($this->isDataEndPayload($line)) {
            $json = self::extractDataFromString($this->buffer);

            if ((false === $json) or !(is_string($json))) {
                return false;
            }

            $assoc_as_array = true; // JSON_OBJECT_AS_ARRAY
            $array = json_decode($json, $assoc_as_array);
            if (null === $array) {
                return false;
            }

            $this->resetFlags();
            $this->clearBuffer();

            return $array;
        }

        return false;
    }

    protected function clearBuffer(): void
    {
        $this->buffer = '';
    }

    protected function isSkippable(string $line): bool
    {
        if (self::isBlank($line) || self::isThump($line)) {
            return true;
        }

        if (self::isByteLenOfPayload($line)) {
            $this->setFlagUpByteLength(true);
            return true;
        }

        return $this->isFlagUpAsEvent($line);
    }

    protected function isFlagUpAsEvent(string $line): bool
    {
        if (! self::isEvent($line)) {
            return false;
        }

        // Reset flags when new event begin
        $this->setFlagUpEventUpdate(false);
        $this->setFlagUpEventDelete(false);
        $this->clearBuffer();

        if (self::isEventDelete($line)) {
            $this->setFlagUpEventDelete(true);
            return true;
        }

        if (self::isEventUpdate($line)) {
            $this->setFlagUpEventUpdate(true);
            return true;
        }

        // Unsupported flag
        return false;
    }

    protected function isFlagUpEventDelete(): bool
    {
        return ($this->flagByteLen && $this->flagEventDelete);
    }

    protected function isFlagUpEventUpdate(): bool
    {
        return ($this->flagByteLen && $this->flagEventUpdate);
    }

    protected function resetFlags(): void
    {
        $this->setFlagUpByteLength(false);
        $this->setFlagUpEventDelete(false);
        $this->setFlagUpEventUpdate(false);
    }

    protected function setFlagUpByteLength(bool $flag): void
    {
        $this->flagByteLen = $flag;
    }

    protected function setFlagUpEventUpdate(bool $flag): void
    {
        $this->flagEventUpdate = $flag;
    }

    protected function setFlagUpEventDelete(bool $flag): void
    {
        $this->flagEventDelete = $flag;
    }
}
