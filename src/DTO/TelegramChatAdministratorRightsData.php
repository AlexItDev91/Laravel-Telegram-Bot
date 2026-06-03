<?php

namespace AlexItDev91\LaravelTelegramBot\DTO;

final readonly class TelegramChatAdministratorRightsData extends TelegramObjectData
{
    public function isAnonymous(): ?bool
    {
        return $this->bool('is_anonymous');
    }

    public function canManageChat(): ?bool
    {
        return $this->bool('can_manage_chat');
    }

    public function canDeleteMessages(): ?bool
    {
        return $this->bool('can_delete_messages');
    }

    public function canManageVideoChats(): ?bool
    {
        return $this->bool('can_manage_video_chats');
    }

    public function canRestrictMembers(): ?bool
    {
        return $this->bool('can_restrict_members');
    }

    public function canPromoteMembers(): ?bool
    {
        return $this->bool('can_promote_members');
    }

    public function canChangeInfo(): ?bool
    {
        return $this->bool('can_change_info');
    }

    public function canInviteUsers(): ?bool
    {
        return $this->bool('can_invite_users');
    }

    public function canPostStories(): ?bool
    {
        return $this->bool('can_post_stories');
    }

    public function canEditStories(): ?bool
    {
        return $this->bool('can_edit_stories');
    }

    public function canDeleteStories(): ?bool
    {
        return $this->bool('can_delete_stories');
    }
}
