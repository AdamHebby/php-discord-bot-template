<?php

namespace DiscordBot\Command;

use DateTime;
use Discord\Builders\MessageBuilder;
use Discord\Discord;
use Discord\Parts\Embed\Embed;
use Discord\Parts\Interactions\Interaction;
use DiscordBot\Application\Benchmarker;
use DiscordBot\Repository\ExampleRepository;
use DiscordBot\Repository\GuildSettingsRepository;
use Psr\Log\LoggerInterface;

class HelloWorld extends Command
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly GuildSettingsRepository $guildSettingsRepository,
        private readonly ExampleRepository $exampleRepository,
        private readonly Discord $discord,
    ) {
    }

    public function getName(): string
    {
        return 'hello';
    }

    public function getDescription(): string
    {
        return 'Hello World Command';
    }

    public function getOptions(): array
    {
        return [];
    }

    public function execute(Interaction $interaction): void
    {
        Benchmarker::getInstance()->start(__CLASS__);

        $settings = $this->guildSettingsRepository->load($interaction->guild_id);

        $example = $this->exampleRepository->get(123);

        $embed = new Embed($this->discord, [
            'title'  => 'Example Embed',
            'url'    => 'https://google.com',
            'image'  => ['url' => 'https://placehold.co/600x400/EEE/31343C'],
            'author' => [
                'name'     => 'Example Bot',
                'url'      => 'https://google.com',
                'icon_url' => 'https://placehold.co/64x64/EEE/31343C',
            ],
            'color'  => hexdec('6a44bc'),
            'timestamp' => (new DateTime())->format('c'),
            'fields' => array_filter([
                [
                    'name'  => 'Example Field',
                    'value' => 'Value',
                    'inline' => true,
                ],
            ]),
            'footer' => [
                'text' => 'Made by .bbno$',
            ]
        ]);


        $message = sprintf(
            'Hello World! This is a test command. You are in guild "%s" (%s)',
            $interaction->guild->name,
            $interaction->guild_id
        );

        $interaction->respondWithMessage(MessageBuilder::new()->addEmbed($embed)->setContent($message));

        $this->logger->info(sprintf(
            'Hello World command shown to user %s (%s) in guild "%s" (%s)',
            $interaction->member->username,
            $interaction->member->id,
            $interaction->guild->name,
            $interaction->guild_id
        ));

        Benchmarker::getInstance()->stop(__CLASS__);
    }
}
