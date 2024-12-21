<?php

namespace DiscordBot\Application;

use Discord\Parts\Embed\Embed;

class EmbeddedMessage
{
    public function __construct(
        public readonly string $message,
        public readonly Embed $embed,
    ) {
    }
}
