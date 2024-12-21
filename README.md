# php-discord-bot-template
This is a custom PHP Discord Bot Framework template, it's designed to be a simple starting point for a bot that can be used to interact with Discord.

# Requirements
PHP >= 8.2
PHP pdo, sqlite, json (events will also speed it up)

# Setup
```bash
git clone git@github.com:AdamHebby/php-discord-bot-template.git
composer install -o
cp .env.example .env
```

Modify .env with all of your secrets, deploy it to your server (or dont) and run it with `php bin/bot.php`.

Make the bot in Discord with the following permissions https://discordlookup.com/permissions-calculator/35461434182976

# Usage
## Prerequisites
Assuming at this point:
 - You've cloned this repo and followed the setup guide
 - Made your bot in Discord with the correct permissions

`php bin/bot.php` will start the bot, it will run until you stop it.

The bot will register several commands, take a look in `src/Command` for the list of commands.

NOTE: These are subject to change, but currently the following commands are available:

```txt
Commands:
 - /help - Show this message.
```

## Adding Commands
This loosely follows Domain Driven Design, mainly because I like the structure it provides. DDD is purely optional and there isn't a set structure, it's more of a philosophy. Basically it keeps things seperated.

Commands, Models, Repositories, Services, etc. are all in their own folders. This is to keep things clean and easy to find. Everything under Application is a service / business logic. There is usually an Infrastructure layer for things like databases and networking / API clients, but this is a simple bot so it's not needed.

There is a lot of DDD that I haven't implemented as it's not required.

To add a command, create a new file in `src/Command` and extend `Command`. You can then implement the `execute` method. The Help command is a good example of how to do this. Make sure to add the command to the `CommandHandler` within `Bot.php`.

Repositories are _mostly_ instantiated in the `Bot.php` file, they can either use the `JsonRepository` or the `SQLiteRepository` depending on your needs.

# Disclaimer
I am not responsible for what you do with this bot, be careful with rate limits and terms of service. Ensure you read the ToS of any service you use this bot with.

Feel free to look over the code, it's not perfect but it's currently only intended for personal use.
