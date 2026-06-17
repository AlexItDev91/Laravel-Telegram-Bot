<?php

namespace AlexItDev91\LaravelTelegramBot\Support;

final class TelegramUpdateChatDiscovery
{
    /**
     * @param  list<array<string, mixed>>  $updates
     * @return list<array<string, string>>
     */
    public function rows(array $updates): array
    {
        $rows = [];

        foreach ($updates as $update) {
            foreach ($this->references($update) as $reference) {
                $rows[] = $reference;
            }
        }

        return $rows;
    }

    /**
     * @param  list<array<string, string>>  $rows
     * @return list<string>
     */
    public function envLines(array $rows): array
    {
        $lines = [];

        foreach ($rows as $index => $row) {
            $suffix = $index === 0 ? '' : '_'.($index + 1);
            $lines[] = 'TELEGRAM_CHAT_ID'.$suffix.'='.$row['chat_id'];

            if (($row['message_thread_id'] ?? '') !== '') {
                $lines[] = 'TELEGRAM_MESSAGE_THREAD_ID'.$suffix.'='.$row['message_thread_id'];
            }

            if (($row['direct_messages_topic_id'] ?? '') !== '') {
                $lines[] = 'TELEGRAM_DIRECT_MESSAGES_TOPIC_ID'.$suffix.'='.$row['direct_messages_topic_id'];
            }
        }

        return $lines;
    }

    /**
     * @param  list<array<string, string>>  $rows
     * @return list<string>
     */
    public function referenceLines(array $rows): array
    {
        $lines = [];

        foreach ($rows as $index => $row) {
            if ($index > 0) {
                $lines[] = '';
            }

            $heading = ($row['update_id'] ?? '') !== '' ? 'Update '.$row['update_id'] : 'Update';

            if (($row['update_type'] ?? '') !== '') {
                $heading .= ' ('.$row['update_type'].')';
            }

            $lines[] = $heading;

            foreach (['source', 'chat_id', 'message_thread_id', 'direct_messages_topic_id', 'message_id', 'chat_type', 'chat_title'] as $key) {
                if (($row[$key] ?? '') === '') {
                    continue;
                }

                $lines[] = '  '.$key.': '.$row[$key];
            }
        }

        return $lines;
    }

    /**
     * @param  array<string, mixed>  $update
     * @return list<array<string, string>>
     */
    private function references(array $update): array
    {
        $updateId = $this->stringValue($update['update_id'] ?? null);
        $rows = [];

        foreach ($this->messagePaths() as $path) {
            $message = $this->arrayAt($update, $path);

            if ($message === null) {
                continue;
            }

            $chat = $this->arrayAt($message, 'chat');

            if ($chat === null) {
                continue;
            }

            $rows[] = $this->row(
                updateId: $updateId,
                updateType: $this->updateType($update),
                source: $path,
                chat: $chat,
                messageId: $message['message_id'] ?? null,
                messageThreadId: $message['message_thread_id'] ?? null,
                directMessagesTopicId: $message['direct_messages_topic_id'] ?? null,
            );
        }

        foreach ($this->chatPaths() as $path) {
            $chat = $this->arrayAt($update, $path);

            if ($chat === null) {
                continue;
            }

            $rows[] = $this->row(
                updateId: $updateId,
                updateType: $this->updateType($update),
                source: $path,
                chat: $chat,
            );
        }

        return $this->uniqueRows($rows);
    }

    /**
     * @return list<string>
     */
    private function messagePaths(): array
    {
        return [
            'message',
            'edited_message',
            'channel_post',
            'edited_channel_post',
            'business_message',
            'edited_business_message',
            'guest_message',
            'callback_query.message',
        ];
    }

    /**
     * @return list<string>
     */
    private function chatPaths(): array
    {
        return [
            'my_chat_member.chat',
            'chat_member.chat',
            'chat_join_request.chat',
            'message_reaction.chat',
            'message_reaction_count.chat',
            'chat_boost.chat',
            'removed_chat_boost.chat',
        ];
    }

    /**
     * @param  array<string, mixed>  $chat
     * @return array<string, string>
     */
    private function row(
        string $updateId,
        string $updateType,
        string $source,
        array $chat,
        mixed $messageId = null,
        mixed $messageThreadId = null,
        mixed $directMessagesTopicId = null,
    ): array {
        return [
            'update_id' => $updateId,
            'update_type' => $updateType,
            'source' => $source,
            'chat_id' => $this->stringValue($chat['id'] ?? null),
            'message_thread_id' => $this->stringValue($messageThreadId),
            'direct_messages_topic_id' => $this->stringValue($directMessagesTopicId),
            'message_id' => $this->stringValue($messageId),
            'chat_type' => $this->stringValue($chat['type'] ?? null),
            'chat_title' => $this->chatTitle($chat),
        ];
    }

    /**
     * @param  array<string, mixed>  $update
     */
    private function updateType(array $update): string
    {
        foreach ($update as $key => $value) {
            if ($key !== 'update_id' && is_array($value)) {
                return $key;
            }
        }

        return '';
    }

    /**
     * @param  array<string, mixed>  $value
     * @return array<string, mixed>|null
     */
    private function arrayAt(array $value, string $path): ?array
    {
        foreach (explode('.', $path) as $segment) {
            if (! is_array($value) || ! is_array($value[$segment] ?? null)) {
                return null;
            }

            /** @var array<string, mixed> $value */
            $value = $value[$segment];
        }

        return $value;
    }

    /**
     * @param  array<string, mixed>  $chat
     */
    private function chatTitle(array $chat): string
    {
        foreach (['title', 'username', 'first_name'] as $key) {
            if (is_string($chat[$key] ?? null) && trim($chat[$key]) !== '') {
                return $chat[$key];
            }
        }

        return '';
    }

    private function stringValue(mixed $value): string
    {
        return match (true) {
            is_int($value) => (string) $value,
            is_float($value) => number_format($value, 0, '.', ''),
            is_string($value) => $value,
            default => '',
        };
    }

    /**
     * @param  list<array<string, string>>  $rows
     * @return list<array<string, string>>
     */
    private function uniqueRows(array $rows): array
    {
        $unique = [];

        foreach ($rows as $row) {
            $key = implode('|', [
                $row['update_id'],
                $row['source'],
                $row['chat_id'],
                $row['message_thread_id'],
                $row['direct_messages_topic_id'],
                $row['message_id'],
            ]);

            $unique[$key] = $row;
        }

        return array_values($unique);
    }
}
