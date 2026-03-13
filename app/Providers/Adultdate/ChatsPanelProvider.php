<?php

namespace App\Providers\Adultdate;

use Adultdate\Wirechat\Support\Color;
use Adultdate\Wirechat\Support\Enums\EmojiPickerPosition;
use Adultdate\Wirechat\Panel;
use Adultdate\Wirechat\PanelProvider;

class ChatsPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('chats')
            ->path('chats')
            ->middleware(['web', 'auth'])
            ->guards(['web'])
            ->chatsSearch()
            ->emojiPicker(position: EmojiPickerPosition::Docked)
            ->webPushNotifications()
            ->messagesQueue('messages')
            ->eventsQueue('default')
          //   ->layout('layouts.app')
            ->attachments()
            ->fileAttachments()
            ->mediaAttachments()
            ->colors([
                'primary' => Color::Blue,
                'danger' => Color::Rose,
                'gray' => Color::Gray,
                'info' => Color::Blue,
                'success' => Color::Emerald,
                'warning' => Color::Orange,
            ])
            ->heading('Chats')
            ->favicon(url: asset('favicon.ico'))
            ->createChatAction()
            ->redirectToHomeAction()
            ->createGroupAction()
            ->maxGroupMembers(10)
            ->homeUrl('/dashboard')
            ->deleteMessageActions(false)
            ->clearChatAction()
            ->mediaMaxUploadSize(12288)
            ->maxUploads(10)
            ->serviceWorkerPath(asset('js/wirechat/sw.js'))
            ->fileMimes(['zip', 'pdf', 'txt'])
            ->mediaMimes(['png', 'jpg', 'mp4'])
            ->default();
    }
}