<?php

namespace DiscordBot;

use Discord\Discord;
use Discord\Parts\Channel\Message;
use Discord\Parts\Interactions\Interaction;
use Discord\WebSockets\Event;
use DiscordBot\Application\Benchmarker;
use DiscordBot\Command\CommandHandler;
use DiscordBot\Command\HelloWorld;
use DiscordBot\Command\Help;
use DiscordBot\Repository\ExampleRepository;
use DiscordBot\Repository\GuildSettingsRepository;
use Psr\Log\LoggerInterface;

class Bot
{
    private CommandHandler $commandHandler;

    public function __construct(
        private readonly Discord $discord,
        private readonly LoggerInterface $logger,
        private readonly GuildSettingsRepository $guildSettingsRepository,
        private readonly ExampleRepository $exampleRepository,
    ) {
        $this->commandHandler = new CommandHandler();

        $this->commandHandler->addCommands([
            new Help($this->logger, $this->commandHandler),
            new HelloWorld($this->logger, $this->guildSettingsRepository, $this->exampleRepository, $this->discord),
        ]);
    }

    public function run(): void
    {
        $this->discord->on('ready', function () {
            foreach ($this->commandHandler->getCommands() as $cmd) {
                $this->discord->application->commands->save(
                    $this->discord->application->commands->create(
                        $cmd->getCommand($this->discord)->toArray()
                    )
                );

                $this->logger->info("Command /{$cmd->getName()} registered");
            }

            $this->logger->info('Bot is ready!');
        });

        $this->discord->on(Event::INTERACTION_CREATE, function (Interaction $interaction) {
            $this->logger->info('Interaction received: /' . $interaction->data->name);
            $this->commandHandler->handle($interaction);
        });

        $this->discord->on(Event::MESSAGE_CREATE, function (Message $message) {
            if ($message->content === '!db restart' && $message->author->id === $_ENV['DISCORD_OWNER_ID']) {
                $message->react('👍')->done(function () {
                    sleep(3);
                    $this->logger->info('Killing bot...');
                    $this->discord->close();
                    exit();
                });
            }
        });

        $this->discord->getLoop()->addPeriodicTimer(60, function () {
            try {
                $this->logger->info('60 second periodic timer - Use or delete this timer');
            } catch (\Throwable $th) {
                $this->logger->error($th->getMessage());
                $this->logger->error($th->getTraceAsString());
            }
        });

        $this->discord->getLoop()->addPeriodicTimer(15, function () {
            touch(__DIR__ . '/../lock');
        });

        $this->discord->run();
    }
}
