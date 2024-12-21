<?php

namespace DiscordBot\Model;

class GuildSettings
{
    public function __construct(
        private string $guildId,
        // ... more data here to store
    ) {
    }

    public function getGuildId(): string
    {
        return $this->guildId;
    }

    /**
     * @param array<string,mixed> $data
     */
    public static function fromJsonArray(array $data): self
    {
        return new self(
            $data['guildId'],
            // ... more data here to store
        );
    }

    /**
     * @return array<string,mixed>
     */
    public function toJsonArray(): array
    {
        return [
            'guildId' => $this->guildId,
            // ... more data here to store
        ];
    }
}
