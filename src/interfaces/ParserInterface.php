<?php

namespace KEINOS\MSTDN_TOOLS;

interface ParserInterface
{
    /**
     * Buffers input until it fulfills the whole data unit. The returned data unit
     * structure is as below:
     *
     *   ```json
     *   {
     *     "event":"[Event name]",
     *     "payload":"[Data of the event]"
     *   }
     *   ```
     *
     * - [Event name]
     *   Event types. Currently we support only the below two events.
     *     - "update": Toot statuses.
     *     - "delete": Toot that was requested to delete.
     *     - For other events see:
     *       - https://docs.joinmastodon.org/methods/timelines/streaming/#event-types-a-idevent-typesa
     * - [Data of the event]
     *   The content/payload of the event.
     *   - "update": If the event is "update" then contains an escaped unicode
     *               and slash escaped toot status entity. The format of the
     *               entity see:
     *                 https://docs.joinmastodon.org/entities/status/
     *   - "delete": If the event is "delete" then it contains the Toot ID to
     *               be deleted.
     *
     * @param  string $line   Received streaming line.
     * @return bool|string    Returns the data unit in JSON string. Or false if the
     *                        status is buffering in progress.
     */
    public function parse(string $line);
}
