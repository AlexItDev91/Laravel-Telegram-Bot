<?php

namespace AlexItDev91\LaravelTelegramBot\DTO;

final readonly class TelegramChatMemberData implements TelegramBotData
{
    /**
     * @param  array<string, mixed>  $payload
     */
    private function __construct(
        private array $payload,
    ) {
        //
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public static function fromPayload(array $payload): self
    {
        return new self($payload);
    }

    public function status(): ?string
    {
        return $this->stringAt('status');
    }

    public function user(): ?TelegramUserData
    {
        $user = $this->payload['user'] ?? null;

        return is_array($user) ? TelegramUserData::fromPayload($user) : null;
    }

    public function isAnonymous(): ?bool
    {
        return $this->boolAt('is_anonymous');
    }

    public function customTitle(): ?string
    {
        return $this->stringAt('custom_title');
    }

    public function tag(): ?string
    {
        return $this->stringAt('tag');
    }

    public function untilDate(): ?int
    {
        return $this->intAt('until_date');
    }

    public function canBeEdited(): ?bool
    {
        return $this->boolAt('can_be_edited');
    }

    public function canManageChat(): ?bool
    {
        return $this->boolAt('can_manage_chat');
    }

    public function canDeleteMessages(): ?bool
    {
        return $this->boolAt('can_delete_messages');
    }

    public function canManageVideoChats(): ?bool
    {
        return $this->boolAt('can_manage_video_chats');
    }

    public function canRestrictMembers(): ?bool
    {
        return $this->boolAt('can_restrict_members');
    }

    public function canPromoteMembers(): ?bool
    {
        return $this->boolAt('can_promote_members');
    }

    public function canChangeInfo(): ?bool
    {
        return $this->boolAt('can_change_info');
    }

    public function canInviteUsers(): ?bool
    {
        return $this->boolAt('can_invite_users');
    }

    public function canPostStories(): ?bool
    {
        return $this->boolAt('can_post_stories');
    }

    public function canEditStories(): ?bool
    {
        return $this->boolAt('can_edit_stories');
    }

    public function canDeleteStories(): ?bool
    {
        return $this->boolAt('can_delete_stories');
    }

    public function canPostMessages(): ?bool
    {
        return $this->boolAt('can_post_messages');
    }

    public function canEditMessages(): ?bool
    {
        return $this->boolAt('can_edit_messages');
    }

    public function canPinMessages(): ?bool
    {
        return $this->boolAt('can_pin_messages');
    }

    public function canManageTopics(): ?bool
    {
        return $this->boolAt('can_manage_topics');
    }

    public function canManageTags(): ?bool
    {
        return $this->boolAt('can_manage_tags');
    }

    public function canEditTag(): ?bool
    {
        return $this->boolAt('can_edit_tag');
    }

    public function canSendMessages(): ?bool
    {
        return $this->boolAt('can_send_messages');
    }

    public function canSendAudios(): ?bool
    {
        return $this->boolAt('can_send_audios');
    }

    public function canSendDocuments(): ?bool
    {
        return $this->boolAt('can_send_documents');
    }

    public function canSendPhotos(): ?bool
    {
        return $this->boolAt('can_send_photos');
    }

    public function canSendVideos(): ?bool
    {
        return $this->boolAt('can_send_videos');
    }

    public function canSendVideoNotes(): ?bool
    {
        return $this->boolAt('can_send_video_notes');
    }

    public function canSendVoiceNotes(): ?bool
    {
        return $this->boolAt('can_send_voice_notes');
    }

    public function canSendPolls(): ?bool
    {
        return $this->boolAt('can_send_polls');
    }

    public function canSendOtherMessages(): ?bool
    {
        return $this->boolAt('can_send_other_messages');
    }

    public function canAddWebPagePreviews(): ?bool
    {
        return $this->boolAt('can_add_web_page_previews');
    }

    public function canReactToMessages(): ?bool
    {
        return $this->boolAt('can_react_to_messages');
    }

    public function isMember(): ?bool
    {
        return $this->boolAt('is_member');
    }

    /**
     * @return array<string, mixed>
     */
    #[\Override]
    public function toArray(): array
    {
        return $this->payload;
    }

    private function boolAt(string $key): ?bool
    {
        $value = $this->payload[$key] ?? null;

        return is_bool($value) ? $value : null;
    }

    private function intAt(string $key): ?int
    {
        $value = $this->payload[$key] ?? null;

        return is_int($value) ? $value : null;
    }

    private function stringAt(string $key): ?string
    {
        $value = $this->payload[$key] ?? null;

        return is_string($value) ? $value : null;
    }
}
