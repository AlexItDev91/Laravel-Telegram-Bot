#!/usr/bin/env php
<?php

declare(strict_types=1);

use AlexItDev91\LaravelTelegramBot\DTO\Requests\TelegramBotApiRequestData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotAccessSettingsData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotCommandData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotDescriptionData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotNameData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotShortDescriptionData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBusinessConnectionData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramChatAdministratorRightsData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramChatFullInfoData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramChatInviteLinkData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramChatMemberData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramFileData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramForumTopicData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramGameHighScoreData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramGiftsData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramMenuButtonData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramMessageData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramMessageIdData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramOwnedGiftsData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramPollData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramPreparedInlineMessageData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramPreparedKeyboardButtonData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramSentGuestMessageData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramSentWebAppMessageData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramStarAmountData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramStarTransactionsData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramStickerData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramStickerSetData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramStoryData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramUserChatBoostsData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramUserData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramUserProfileAudiosData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramUserProfilePhotosData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramWebhookInfoData;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramWebhookUpdate;
use AlexItDev91\LaravelTelegramBot\Enums\TelegramBotApiMethod;
use AlexItDev91\LaravelTelegramBot\Enums\TelegramChatAction;
use AlexItDev91\LaravelTelegramBot\Enums\TelegramParseMode;
use AlexItDev91\LaravelTelegramBot\Enums\TelegramPollType;
use AlexItDev91\LaravelTelegramBot\Enums\TelegramStickerFormat;
use AlexItDev91\LaravelTelegramBot\Enums\TelegramStickerType;
use AlexItDev91\LaravelTelegramBot\Enums\TelegramUpdateType;

require __DIR__.'/../vendor/autoload.php';

$root = dirname(__DIR__);
$methodsPath = $root.'/docs/METHODS.md';
$targetPath = $root.'/src/TelegramBotApiMethodSchema.php';
$requestRegistryPath = $root.'/src/TelegramBotApiRequestRegistry.php';
$resultSchemaPath = $root.'/src/TelegramBotApiResultSchema.php';
$requestBasePath = $root.'/src/DTO/Requests/TelegramBotApiRequestData.php';
$requestDirectory = $root.'/src/DTO/Requests';

$markdown = file_get_contents($methodsPath);

if ($markdown === false) {
    fwrite(STDERR, "Failed to read $methodsPath.\n");
    exit(1);
}

$schema = [];

preg_match_all('/^### `([^`]+)`\R(.*?)(?=^### `|\z)/ms', $markdown, $sections, PREG_SET_ORDER);

foreach ($sections as $section) {
    [, $method, $body] = $section;

    preg_match_all('/^\| `([^`]+)` \| `([^`]+)` \| `(Yes|Optional)` \|$/m', $body, $rows, PREG_SET_ORDER);

    $schema[$method] = array_map(
        static fn (array $row): array => [
            'name' => $row[1],
            'type' => preg_replace('/\s+/', ' ', $row[2]) ?? $row[2],
            'required' => $row[3] === 'Yes',
        ],
        $rows,
    );
}

ksort($schema);

$export = shortArrayExport($schema, 2);
$checksum = hash('sha256', json_encode($schema, JSON_THROW_ON_ERROR));
$content = <<<PHP
<?php

namespace AlexItDev91\\LaravelTelegramBot;

use AlexItDev91\\LaravelTelegramBot\\Enums\\TelegramBotApiMethod;

/**
 * Generated from docs/METHODS.md by scripts/generate-telegram-api-schema.php.
 */
final class TelegramBotApiMethodSchema
{
    public const string CHECKSUM = '$checksum';

    /**
     * @var array<string, list<array{name: string, type: string, required: bool}>>
     */
    private const array PARAMETERS = $export;

    public static function supports(string|TelegramBotApiMethod \$method): bool
    {
        return array_key_exists(self::methodName(\$method), self::PARAMETERS);
    }

    /**
     * @return list<array{name: string, type: string, required: bool}>
     */
    public static function parameters(string|TelegramBotApiMethod \$method): array
    {
        return self::PARAMETERS[self::methodName(\$method)] ?? [];
    }

    /**
     * @return list<string>
     */
    public static function requiredParameters(string|TelegramBotApiMethod \$method): array
    {
        return array_values(array_map(
            static fn (array \$parameter): string => \$parameter['name'],
            array_filter(self::parameters(\$method), static fn (array \$parameter): bool => \$parameter['required']),
        ));
    }

    /**
     * @return array<string, list<array{name: string, type: string, required: bool}>>
     */
    public static function all(): array
    {
        return self::PARAMETERS;
    }

    public static function checksum(): string
    {
        return self::CHECKSUM;
    }

    private static function methodName(string|TelegramBotApiMethod \$method): string
    {
        return \$method instanceof TelegramBotApiMethod ? \$method->value : \$method;
    }
}

PHP;

if (file_put_contents($targetPath, $content) === false) {
    fwrite(STDERR, "Failed to write $targetPath.\n");
    exit(1);
}

ensureDirectory($requestDirectory);
writeRequestBase($requestBasePath);

$requestClasses = [];

foreach ($schema as $method => $parameters) {
    $className = requestClassName($method);
    $requestClasses[$method] = 'AlexItDev91\\LaravelTelegramBot\\DTO\\Requests\\'.$className;

    $requestPath = $requestDirectory.'/'.$className.'.php';
    writeFile($requestPath, requestClassContent($method, $parameters, $className));
}

ksort($requestClasses);
writeFile($requestRegistryPath, requestRegistryContent($requestClasses));
writeFile($resultSchemaPath, resultSchemaContent(resultSchema($schema)));

printf(
    "Generated %s, %s, %s and %d request classes with %d methods and %d parameters.\n",
    $targetPath,
    $requestRegistryPath,
    $resultSchemaPath,
    count($requestClasses),
    count($schema),
    array_sum(array_map('count', $schema)),
);

function ensureDirectory(string $directory): void
{
    if (is_dir($directory)) {
        foreach (glob($directory.'/*RequestData.php') ?: [] as $path) {
            if (basename($path) !== 'TelegramBotApiRequestData.php') {
                unlink($path);
            }
        }

        return;
    }

    if (! mkdir($directory, 0775, true) && ! is_dir($directory)) {
        fwrite(STDERR, "Failed to create $directory.\n");
        exit(1);
    }
}

function writeFile(string $path, string $content): void
{
    if (file_put_contents($path, $content) === false) {
        fwrite(STDERR, "Failed to write $path.\n");
        exit(1);
    }
}

function writeRequestBase(string $path): void
{
    $content = <<<'PHP'
<?php

namespace AlexItDev91\LaravelTelegramBot\DTO\Requests;

use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotMethodRequest;
use AlexItDev91\LaravelTelegramBot\DTO\TelegramBotRequestData;
use AlexItDev91\LaravelTelegramBot\TelegramBotApiMethodSchema;
use InvalidArgumentException;
use Override;

/**
 * @phpstan-consistent-constructor
 */
abstract readonly class TelegramBotApiRequestData extends TelegramBotRequestData implements TelegramBotMethodRequest
{
    public const string METHOD = '';

    /**
     * @param  array<string, mixed>  $parameters
     */
    final public function __construct(array $parameters = [], private bool $validateRequiredParameters = true)
    {
        if ($this->validateRequiredParameters) {
            $this->assertRequiredParameters($parameters);
        }

        parent::__construct($parameters);
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public static function fromParameters(array $parameters = [], bool $validateRequiredParameters = true): static
    {
        return new static($parameters, $validateRequiredParameters);
    }

    public function withoutRequiredValidation(): static
    {
        return new static($this->parameters, false);
    }

    public function with(string $parameter, mixed $value): static
    {
        return new static(array_merge($this->parameters, [$parameter => $value]), $this->validatesRequiredParameters());
    }

    #[Override]
    public function method(): string
    {
        return static::METHOD;
    }

    #[Override]
    public function validatesRequiredParameters(): bool
    {
        return $this->validateRequiredParameters;
    }

    /**
     * @return list<array{name: string, type: string, required: bool}>
     */
    #[Override]
    public function schema(): array
    {
        return TelegramBotApiMethodSchema::parameters($this->method());
    }

    /**
     * @return list<string>
     */
    #[Override]
    public function requiredParameters(): array
    {
        return TelegramBotApiMethodSchema::requiredParameters($this->method());
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    private function assertRequiredParameters(array $parameters): void
    {
        $missing = array_values(array_filter(
            $this->requiredParameters(),
            static fn (string $parameter): bool => ! array_key_exists($parameter, $parameters) || $parameters[$parameter] === null,
        ));

        if ($missing === []) {
            return;
        }

        throw new InvalidArgumentException(sprintf(
            'Telegram Bot API method [%s] requires parameter(s): %s.',
            $this->method(),
            implode(', ', $missing),
        ));
    }
}

PHP;

    writeFile($path, $content);
}

/**
 * @param  list<array{name: string, type: string, required: bool}>  $parameters
 */
function requestClassContent(string $method, array $parameters, string $className): string
{
    $uses = [];
    $payloadLines = [];
    $signatureParameters = [];
    $docblocks = [];

    foreach (orderedParameters($parameters) as $parameter) {
        $parameterName = $parameter['name'];
        $variable = parameterVariable($parameterName);
        $type = phpParameterType($method, $parameterName, $parameter['type'], $parameter['required']);

        if ($type['usesInputFile']) {
            $uses[] = 'AlexItDev91\\LaravelTelegramBot\\InputFile';
        }

        if ($type['usesTelegramBotData']) {
            $uses[] = 'AlexItDev91\\LaravelTelegramBot\\DTO\\TelegramBotData';
        }

        foreach ($type['uses'] as $use) {
            $uses[] = $use;
        }

        if ($type['doc'] !== null) {
            $docblocks[] = sprintf('     * @param  %s  $%s', $type['doc'], $variable);
        }

        $default = $parameter['required'] ? '' : ' = null';
        $signatureParameters[] = sprintf('        %s $%s%s', $type['php'], $variable, $default);
        $payloadLines[] = sprintf("            '%s' => \$%s,", $parameterName, $variable);
    }

    $docblocks[] = '     * @param  array<string, mixed>  $extra';

    $signature = $signatureParameters === []
        ? '        array $extra = [],'
        : implode(",\n", array_merge($signatureParameters, ['        array $extra = [],']));

    $payload = $payloadLines === []
        ? '[]'
        : "[\n".implode("\n", $payloadLines)."\n        ]";

    $useLines = implode("\n", array_map(static fn (string $use): string => 'use '.$use.';', array_values(array_unique($uses))));
    $docblock = "    /**\n".implode("\n", $docblocks)."\n     */";

    return <<<PHP
<?php

namespace AlexItDev91\\LaravelTelegramBot\\DTO\\Requests;

$useLines

/**
 * Generated typed request builder for Telegram Bot API method `$method`.
 */
final readonly class $className extends TelegramBotApiRequestData
{
    public const string METHOD = '$method';

$docblock
    public static function make(
$signature
    ): self {
        return new self(self::withoutNullValues(array_merge($payload, \$extra)));
    }
}

PHP;
}

/**
 * @param  list<array{name: string, type: string, required: bool}>  $parameters
 * @return list<array{name: string, type: string, required: bool}>
 */
function orderedParameters(array $parameters): array
{
    usort(
        $parameters,
        static fn (array $left, array $right): int => ((int) ! $left['required']) <=> ((int) ! $right['required']),
    );

    return $parameters;
}

/**
 * @return array{php: string, usesInputFile: bool, usesTelegramBotData: bool, uses: list<string>, doc: string|null}
 */
function phpParameterType(string $method, string $parameterName, string $telegramType, bool $required): array
{
    $normalized = strtolower($telegramType);
    $usesInputFile = str_contains($telegramType, 'InputFile');
    $usesTelegramBotData = false;
    $uses = [];
    $isArray = str_starts_with($telegramType, 'Array of ');
    $enumClass = enumClassForParameter($method, $parameterName);

    if ($isArray && $enumClass !== null) {
        $php = 'array';
        $uses[] = $enumClass;
        $doc = 'array<int, string|'.classBasename($enumClass).'>';
    } elseif ($isArray) {
        $php = 'array';
        $doc = 'array<string|int, mixed>';
    } elseif (str_contains($telegramType, 'InputFile') && str_contains($telegramType, 'String')) {
        $php = 'InputFile|string';
        $doc = null;
    } elseif ($telegramType === 'InputFile') {
        $php = 'InputFile';
        $doc = null;
    } elseif ($telegramType === 'Integer or String' || $telegramType === 'String or Integer') {
        $php = 'int|string';
        $doc = null;
    } elseif ($telegramType === 'Integer') {
        $php = 'int';
        $doc = null;
    } elseif ($telegramType === 'String' && $enumClass !== null) {
        $php = 'string|'.classBasename($enumClass);
        $uses[] = $enumClass;
        $doc = null;
    } elseif ($telegramType === 'String') {
        $php = 'string';
        $doc = null;
    } elseif ($telegramType === 'Boolean' || $telegramType === 'True') {
        $php = 'bool';
        $doc = null;
    } elseif ($telegramType === 'Float' || $telegramType === 'Float number') {
        $php = 'float';
        $doc = null;
    } elseif (str_contains($normalized, ' or ')) {
        $php = 'mixed';
        $doc = null;
    } else {
        $php = 'TelegramBotData|array';
        $usesTelegramBotData = true;
        $doc = 'TelegramBotData|array<string|int, mixed>';
    }

    if (! $required) {
        if ($doc !== null) {
            $doc .= '|null';
        }

        $php = match ($php) {
            'array' => '?array',
            'string' => '?string',
            'int' => '?int',
            'bool' => '?bool',
            'float' => '?float',
            'mixed' => 'mixed',
            default => $php.'|null',
        };
    }

    return [
        'php' => $php,
        'usesInputFile' => $usesInputFile,
        'usesTelegramBotData' => $usesTelegramBotData,
        'uses' => $uses,
        'doc' => $doc,
    ];
}

function enumClassForParameter(string $method, string $parameterName): ?string
{
    if (str_ends_with($parameterName, 'parse_mode')) {
        return TelegramParseMode::class;
    }

    return enumParameterBindings()[$method.':'.$parameterName] ?? null;
}

/**
 * @return array<string, class-string>
 */
function enumParameterBindings(): array
{
    return [
        'createNewStickerSet:sticker_type' => TelegramStickerType::class,
        'getUpdates:allowed_updates' => TelegramUpdateType::class,
        'sendChatAction:action' => TelegramChatAction::class,
        'sendPoll:type' => TelegramPollType::class,
        'setWebhook:allowed_updates' => TelegramUpdateType::class,
        'uploadStickerFile:sticker_format' => TelegramStickerFormat::class,
    ];
}

function classBasename(string $class): string
{
    return substr($class, (int) strrpos($class, '\\') + 1);
}

function parameterVariable(string $parameter): string
{
    $camel = lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $parameter))));

    return match ($camel) {
        'this' => 'thisValue',
        default => $camel,
    };
}

function requestClassName(string $method): string
{
    return ucfirst($method).'RequestData';
}

/**
 * @param  array<string, string>  $requestClasses
 */
function requestRegistryContent(array $requestClasses): string
{
    $classImports = classImportMap(array_merge([
        TelegramBotApiMethod::class,
        TelegramBotApiRequestData::class,
    ], array_values($requestClasses)));
    $classUseLines = classUseStatements($classImports);
    $export = shortArrayExport($requestClasses, 2, $classImports);

    return <<<PHP
<?php

namespace AlexItDev91\\LaravelTelegramBot;

$classUseLines

/**
 * Generated from docs/METHODS.md by scripts/generate-telegram-api-schema.php.
 */
final class TelegramBotApiRequestRegistry
{
    /**
     * @var array<string, class-string<TelegramBotApiRequestData>>
     */
    private const array REQUESTS = $export;

    /**
     * @return class-string<TelegramBotApiRequestData>|null
     */
    public static function requestClass(string|TelegramBotApiMethod \$method): ?string
    {
        return self::REQUESTS[self::methodName(\$method)] ?? null;
    }

    /**
     * @return array<string, class-string<TelegramBotApiRequestData>>
     */
    public static function all(): array
    {
        return self::REQUESTS;
    }

    private static function methodName(string|TelegramBotApiMethod \$method): string
    {
        return \$method instanceof TelegramBotApiMethod ? \$method->value : \$method;
    }
}

PHP;
}

/**
 * @param  array<string, list<array{name: string, type: string, required: bool}>>  $schema
 * @return array<string, array{type: string, data_class: string|null, list: bool, allows_bool: bool}>
 */
function resultSchema(array $schema): array
{
    $results = [];

    foreach (array_keys($schema) as $method) {
        $results[$method] = [
            'type' => defaultResultType($method),
            'data_class' => null,
            'list' => false,
            'allows_bool' => false,
        ];
    }

    foreach (messageMethods() as $method) {
        setResult($results, $method, 'Message', TelegramMessageData::class);
    }

    foreach (messageListMethods() as $method) {
        setResult($results, $method, 'Array<Message>', TelegramMessageData::class, list: true);
    }

    foreach (messageIdListMethods() as $method) {
        setResult($results, $method, 'Array<MessageId>', TelegramMessageIdData::class, list: true);
    }

    foreach (messageOrBoolMethods() as $method) {
        setResult($results, $method, 'Message|Boolean', TelegramMessageData::class, allowsBool: true);
    }

    foreach ([
        'createInvoiceLink' => 'String',
        'exportChatInviteLink' => 'String',
        'getChatMemberCount' => 'Integer',
        'getManagedBotToken' => 'String',
        'replaceManagedBotToken' => 'String',
        'sendChatAction' => 'Boolean',
        'sendGift' => 'Boolean',
        'sendMessageDraft' => 'Boolean',
    ] as $method => $type) {
        setScalarResult($results, $method, $type);
    }

    foreach ([
        'getMe' => ['User', TelegramUserData::class],
        'getChat' => ['ChatFullInfo', TelegramChatFullInfoData::class],
        'getChatMember' => ['ChatMember', TelegramChatMemberData::class],
        'getFile' => ['File', TelegramFileData::class],
        'getWebhookInfo' => ['WebhookInfo', TelegramWebhookInfoData::class],
        'getBusinessConnection' => ['BusinessConnection', TelegramBusinessConnectionData::class],
        'getUserChatBoosts' => ['UserChatBoosts', TelegramUserChatBoostsData::class],
        'createChatInviteLink' => ['ChatInviteLink', TelegramChatInviteLinkData::class],
        'createChatSubscriptionInviteLink' => ['ChatInviteLink', TelegramChatInviteLinkData::class],
        'editChatInviteLink' => ['ChatInviteLink', TelegramChatInviteLinkData::class],
        'editChatSubscriptionInviteLink' => ['ChatInviteLink', TelegramChatInviteLinkData::class],
        'revokeChatInviteLink' => ['ChatInviteLink', TelegramChatInviteLinkData::class],
        'createForumTopic' => ['ForumTopic', TelegramForumTopicData::class],
        'getStickerSet' => ['StickerSet', TelegramStickerSetData::class],
        'getAvailableGifts' => ['Gifts', TelegramGiftsData::class],
        'getBusinessAccountGifts' => ['OwnedGifts', TelegramOwnedGiftsData::class],
        'getChatGifts' => ['OwnedGifts', TelegramOwnedGiftsData::class],
        'getUserGifts' => ['OwnedGifts', TelegramOwnedGiftsData::class],
        'getBusinessAccountStarBalance' => ['StarAmount', TelegramStarAmountData::class],
        'getMyStarBalance' => ['StarAmount', TelegramStarAmountData::class],
        'getStarTransactions' => ['StarTransactions', TelegramStarTransactionsData::class],
        'getUserProfilePhotos' => ['UserProfilePhotos', TelegramUserProfilePhotosData::class],
        'getUserProfileAudios' => ['UserProfileAudios', TelegramUserProfileAudiosData::class],
        'getChatMenuButton' => ['MenuButton', TelegramMenuButtonData::class],
        'getMyName' => ['BotName', TelegramBotNameData::class],
        'getMyDescription' => ['BotDescription', TelegramBotDescriptionData::class],
        'getMyShortDescription' => ['BotShortDescription', TelegramBotShortDescriptionData::class],
        'getMyDefaultAdministratorRights' => ['ChatAdministratorRights', TelegramChatAdministratorRightsData::class],
        'copyMessage' => ['MessageId', TelegramMessageIdData::class],
        'stopPoll' => ['Poll', TelegramPollData::class],
        'postStory' => ['Story', TelegramStoryData::class],
        'repostStory' => ['Story', TelegramStoryData::class],
        'editStory' => ['Story', TelegramStoryData::class],
        'answerWebAppQuery' => ['SentWebAppMessage', TelegramSentWebAppMessageData::class],
        'savePreparedInlineMessage' => ['PreparedInlineMessage', TelegramPreparedInlineMessageData::class],
        'savePreparedKeyboardButton' => ['PreparedKeyboardButton', TelegramPreparedKeyboardButtonData::class],
        'answerGuestQuery' => ['SentGuestMessage', TelegramSentGuestMessageData::class],
        'getManagedBotAccessSettings' => ['BotAccessSettings', TelegramBotAccessSettingsData::class],
        'uploadStickerFile' => ['File', TelegramFileData::class],
    ] as $method => [$type, $class]) {
        setResult($results, $method, $type, $class);
    }

    foreach ([
        'getUpdates' => ['Update', TelegramWebhookUpdate::class],
        'getChatAdministrators' => ['ChatMember', TelegramChatMemberData::class],
        'getCustomEmojiStickers' => ['Sticker', TelegramStickerData::class],
        'getForumTopicIconStickers' => ['Sticker', TelegramStickerData::class],
        'getMyCommands' => ['BotCommand', TelegramBotCommandData::class],
        'getGameHighScores' => ['GameHighScore', TelegramGameHighScoreData::class],
    ] as $method => [$type, $class]) {
        setResult($results, $method, 'Array<'.$type.'>', $class, list: true);
    }

    return $results;
}

/**
 * @param  array<string, array{type: string, data_class: string|null, list: bool, allows_bool: bool}>  $results
 */
function setResult(array &$results, string $method, string $type, string $class, bool $list = false, bool $allowsBool = false): void
{
    if (! array_key_exists($method, $results)) {
        return;
    }

    $results[$method] = [
        'type' => $type,
        'data_class' => $class,
        'list' => $list,
        'allows_bool' => $allowsBool,
    ];
}

/**
 * @param  array<string, array{type: string, data_class: string|null, list: bool, allows_bool: bool}>  $results
 */
function setScalarResult(array &$results, string $method, string $type): void
{
    if (! array_key_exists($method, $results)) {
        return;
    }

    $results[$method] = [
        'type' => $type,
        'data_class' => null,
        'list' => false,
        'allows_bool' => false,
    ];
}

function defaultResultType(string $method): string
{
    if (str_starts_with($method, 'get')) {
        return 'mixed';
    }

    if (str_starts_with($method, 'send') || str_starts_with($method, 'forward') || str_starts_with($method, 'copy')) {
        return 'mixed';
    }

    return 'Boolean';
}

/**
 * @return list<string>
 */
function messageMethods(): array
{
    return [
        'forwardMessage',
        'editMessageChecklist',
        'sendAnimation',
        'sendAudio',
        'sendChecklist',
        'sendContact',
        'sendDice',
        'sendDocument',
        'sendGame',
        'sendInvoice',
        'sendLivePhoto',
        'sendLocation',
        'sendMessage',
        'sendPaidMedia',
        'sendPhoto',
        'sendPoll',
        'sendSticker',
        'sendVenue',
        'sendVideo',
        'sendVideoNote',
        'sendVoice',
    ];
}

/**
 * @return list<string>
 */
function messageListMethods(): array
{
    return [
        'sendMediaGroup',
        'getUserPersonalChatMessages',
    ];
}

/**
 * @return list<string>
 */
function messageIdListMethods(): array
{
    return [
        'copyMessages',
        'forwardMessages',
    ];
}

/**
 * @return list<string>
 */
function messageOrBoolMethods(): array
{
    return [
        'editMessageCaption',
        'editMessageLiveLocation',
        'editMessageMedia',
        'editMessageReplyMarkup',
        'editMessageText',
        'setGameScore',
        'stopMessageLiveLocation',
    ];
}

/**
 * @param  array<string, array{type: string, data_class: string|null, list: bool, allows_bool: bool}>  $results
 */
function resultSchemaContent(array $results): string
{
    $classImports = classImportMap(array_merge([
        TelegramBotApiMethod::class,
        TelegramBotData::class,
    ], array_values(array_filter(array_column($results, 'data_class'), 'is_string'))));
    $classUseLines = classUseStatements($classImports);
    $export = shortArrayExport($results, 2, $classImports);

    return <<<PHP
<?php

namespace AlexItDev91\\LaravelTelegramBot;

$classUseLines

/**
 * Generated from docs/METHODS.md by scripts/generate-telegram-api-schema.php.
 */
final class TelegramBotApiResultSchema
{
    /**
     * @var array<string, array{type: string, data_class: class-string<TelegramBotData>|null, list: bool, allows_bool: bool}>
     */
    private const array RESULTS = $export;

    /**
     * @return array{type: string, data_class: class-string<TelegramBotData>|null, list: bool, allows_bool: bool}
     */
    public static function result(string|TelegramBotApiMethod \$method): array
    {
        return self::RESULTS[self::methodName(\$method)] ?? [
            'type' => 'mixed',
            'data_class' => null,
            'list' => false,
            'allows_bool' => false,
        ];
    }

    public static function type(string|TelegramBotApiMethod \$method): string
    {
        return self::result(\$method)['type'];
    }

    /**
     * @return class-string<TelegramBotData>|null
     */
    public static function dataClass(string|TelegramBotApiMethod \$method): ?string
    {
        return self::result(\$method)['data_class'];
    }

    public static function isList(string|TelegramBotApiMethod \$method): bool
    {
        return self::result(\$method)['list'];
    }

    public static function allowsBool(string|TelegramBotApiMethod \$method): bool
    {
        return self::result(\$method)['allows_bool'];
    }

    /**
     * @return array<string, array{type: string, data_class: class-string<TelegramBotData>|null, list: bool, allows_bool: bool}>
     */
    public static function all(): array
    {
        return self::RESULTS;
    }

    private static function methodName(string|TelegramBotApiMethod \$method): string
    {
        return \$method instanceof TelegramBotApiMethod ? \$method->value : \$method;
    }
}

PHP;
}

/**
 * @param  list<string>  $classes
 * @return array<string, string>
 */
function classImportMap(array $classes): array
{
    $imports = [];
    $basenames = [];

    foreach (array_values(array_unique($classes)) as $class) {
        if (! isPackageClassString($class)) {
            continue;
        }

        $basename = classBasename($class);

        if (array_key_exists($basename, $basenames)) {
            throw new RuntimeException(sprintf(
                'Cannot import both %s and %s because they share short class name %s.',
                $basenames[$basename],
                $class,
                $basename,
            ));
        }

        $imports[$class] = $basename;
        $basenames[$basename] = $class;
    }

    ksort($imports);

    return $imports;
}

/**
 * @param  array<string, string>  $classImports
 */
function classUseStatements(array $classImports): string
{
    if ($classImports === []) {
        return '';
    }

    return implode(
        "\n",
        array_map(static fn (string $class): string => 'use '.$class.';', array_keys($classImports)),
    );
}

/**
 * @param  array<string|int, mixed>  $value
 * @param  array<string, string>  $classImports
 */
function shortArrayExport(array $value, int $indent = 1, array $classImports = []): string
{
    $spaces = str_repeat('    ', $indent);
    $outer = str_repeat('    ', $indent - 1);
    $isList = array_is_list($value);
    $lines = ['['];

    foreach ($value as $key => $item) {
        $prefix = $isList ? $spaces : $spaces.var_export($key, true).' => ';

        if (is_array($item)) {
            $lines[] = $prefix.shortArrayExport($item, $indent + 1, $classImports).',';

            continue;
        }

        $lines[] = $prefix.valueExport($item, $classImports).',';
    }

    $lines[] = $outer.']';

    return implode("\n", $lines);
}

/**
 * @param  array<string, string>  $classImports
 */
function valueExport(mixed $value, array $classImports = []): string
{
    if (is_string($value) && array_key_exists($value, $classImports)) {
        return $classImports[$value].'::class';
    }

    if (is_string($value) && isPackageClassString($value)) {
        return '\\'.$value.'::class';
    }

    return var_export($value, true);
}

function isPackageClassString(string $value): bool
{
    return str_starts_with($value, 'AlexItDev91\\LaravelTelegramBot\\')
        && preg_match('/^[A-Za-z_][A-Za-z0-9_]*(?:\\\\[A-Za-z_][A-Za-z0-9_]*)+$/', $value) === 1
        && (class_exists($value) || interface_exists($value) || enum_exists($value));
}
