<?php

namespace AlexItDev91\LaravelTelegramBot\Laravel\Console\Commands;

use Illuminate\Console\Command;
use InvalidArgumentException;

use function Laravel\Prompts\info;

class TelegramBotMakeHandlerCommand extends Command
{
    protected $signature = 'telegram-bot:make-handler
        {name : Handler class name, for example StartCommand or Telegram/Handlers/MessageHandler}
        {--command= : Scaffold a /command handler for the given command name}
        {--update= : Scaffold an update-type handler, for example message or callback_query}
        {--fallback : Scaffold a fallback webhook handler}
        {--force : Overwrite the handler file when it already exists}';

    protected $description = 'Scaffold a Telegram webhook command, update-type, or fallback handler class.';

    public function handle(): int
    {
        try {
            $context = $this->handlerContext();
            [$namespace, $className] = $this->classParts($context['kind']);
            $path = $this->classPath($namespace, $className);
        } catch (InvalidArgumentException $exception) {
            $this->components->error($exception->getMessage());

            return self::FAILURE;
        }

        if (is_file($path) && ! $this->option('force')) {
            $this->components->error("Telegram handler already exists at [$path]. Use --force to overwrite it.");

            return self::FAILURE;
        }

        $directory = dirname($path);
        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        file_put_contents($path, $this->stub($context, $namespace, $className));

        info("Telegram webhook {$context['label']} handler created: $namespace\\$className");
        $this->line("Path: $path");
        $this->line($this->registrationHint($context, $namespace, $className));

        return self::SUCCESS;
    }

    /**
     * @return array{kind: 'command'|'update'|'fallback', label: string, value: string|null}
     */
    private function handlerContext(): array
    {
        $command = $this->optionString('command');
        $update = $this->optionString('update');
        $fallback = (bool) $this->option('fallback');

        $selected = array_filter([
            $command !== null,
            $update !== null,
            $fallback,
        ]);

        if (count($selected) > 1) {
            throw new InvalidArgumentException('Use only one of --command, --update, or --fallback.');
        }

        if ($command !== null) {
            $command = ltrim($command, '/');

            if ($command === '') {
                throw new InvalidArgumentException('The --command option must contain a command name.');
            }

            return ['kind' => 'command', 'label' => 'command', 'value' => $command];
        }

        if ($fallback) {
            return ['kind' => 'fallback', 'label' => 'fallback', 'value' => null];
        }

        return ['kind' => 'update', 'label' => 'update', 'value' => $update ?? 'message'];
    }

    /**
     * @param  'command'|'update'|'fallback'  $kind
     * @return array{string, string}
     */
    private function classParts(string $kind): array
    {
        $argument = $this->argument('name');

        if (! is_string($argument)) {
            throw new InvalidArgumentException('The handler class name must be a string.');
        }

        $name = trim(str_replace('/', '\\', $argument), '\\');

        if ($name === '') {
            throw new InvalidArgumentException('The handler class name must not be empty.');
        }

        if (! str_contains($name, '\\')) {
            $name = match ($kind) {
                'command' => 'Telegram\\Commands\\'.$name,
                default => 'Telegram\\Handlers\\'.$name,
            };
        }

        if (! str_starts_with($name, 'App\\')) {
            $name = 'App\\'.$name;
        }

        $parts = explode('\\', $name);
        $className = array_pop($parts);

        if ($className === null || $className === '' || ! preg_match('/^[A-Za-z_][A-Za-z0-9_]*$/', $className)) {
            throw new InvalidArgumentException('The handler class name must be a valid PHP class name.');
        }

        return [implode('\\', $parts), $className];
    }

    private function classPath(string $namespace, string $className): string
    {
        $relativeNamespace = preg_replace('/^App\\\\?/', '', $namespace);
        $relativePath = str_replace('\\', DIRECTORY_SEPARATOR, (string) $relativeNamespace);

        return app_path(trim($relativePath.DIRECTORY_SEPARATOR.$className.'.php', DIRECTORY_SEPARATOR));
    }

    /**
     * @param  array{kind: 'command'|'update'|'fallback', label: string, value: string|null}  $context
     */
    private function stub(array $context, string $namespace, string $className): string
    {
        return match ($context['kind']) {
            'command' => $this->commandStub($namespace, $className, (string) $context['value']),
            'fallback' => $this->fallbackStub($namespace, $className),
            default => $this->updateStub($namespace, $className, (string) $context['value']),
        };
    }

    private function commandStub(string $namespace, string $className, string $command): string
    {
        $command = $this->quote($command);

        return <<<PHP
<?php

namespace $namespace;

use Override;
use AlexItDev91\\LaravelTelegramBot\\Contracts\\TelegramWebhookCommandHandler;
use AlexItDev91\\LaravelTelegramBot\\DTO\\TelegramWebhookUpdate;
use AlexItDev91\\LaravelTelegramBot\\Laravel\\Attributes\\TelegramCommand;
use AlexItDev91\\LaravelTelegramBot\\Laravel\\TelegramWebhookCommand;
use AlexItDev91\\LaravelTelegramBot\\Laravel\\TelegramWebhookReply;

#[TelegramCommand($command)]
final readonly class $className implements TelegramWebhookCommandHandler
{
    #[Override]
    public function handle(TelegramWebhookCommand \$command, TelegramWebhookUpdate \$update, string \$botName): mixed
    {
        return TelegramWebhookReply::fromUpdate(\$update)->text('Ready.');
    }
}
PHP;
    }

    private function updateStub(string $namespace, string $className, string $updateType): string
    {
        $updateType = $this->quote($updateType);

        return <<<PHP
<?php

namespace $namespace;

use Override;
use AlexItDev91\\LaravelTelegramBot\\Contracts\\TelegramWebhookHandler;
use AlexItDev91\\LaravelTelegramBot\\DTO\\TelegramWebhookUpdate;
use AlexItDev91\\LaravelTelegramBot\\Laravel\\Attributes\\TelegramUpdateHandler;

#[TelegramUpdateHandler($updateType)]
final readonly class $className implements TelegramWebhookHandler
{
    #[Override]
    public function handle(TelegramWebhookUpdate \$update, string \$botName): mixed
    {
        if (\$update->type() !== $updateType) {
            return null;
        }

        return null;
    }
}
PHP;
    }

    private function fallbackStub(string $namespace, string $className): string
    {
        return <<<PHP
<?php

namespace $namespace;

use Override;
use AlexItDev91\\LaravelTelegramBot\\Contracts\\TelegramWebhookHandler;
use AlexItDev91\\LaravelTelegramBot\\DTO\\TelegramWebhookUpdate;

final readonly class $className implements TelegramWebhookHandler
{
    #[Override]
    public function handle(TelegramWebhookUpdate \$update, string \$botName): mixed
    {
        return null;
    }
}
PHP;
    }

    /**
     * @param  array{kind: 'command'|'update'|'fallback', label: string, value: string|null}  $context
     */
    private function registrationHint(array $context, string $namespace, string $className): string
    {
        $class = $namespace.'\\'.$className.'::class';

        return match ($context['kind']) {
            'command' => "Register in config: 'commands' => ['{$context['value']}' => $class]",
            'fallback' => "Register in config: 'fallback_handler' => $class",
            default => "Register in config: 'handlers' => ['{$context['value']}' => $class]",
        };
    }

    private function optionString(string $key): ?string
    {
        $value = $this->option($key);

        return is_string($value) && trim($value) !== '' ? trim($value) : null;
    }

    private function quote(string $value): string
    {
        return var_export($value, true);
    }
}
