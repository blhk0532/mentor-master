<?php

declare(strict_types=1);

namespace WallaceMartinss\FilamentEvolution\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use WallaceMartinss\FilamentEvolution\Enums\MessageDirectionEnum;
use WallaceMartinss\FilamentEvolution\Enums\MessageStatusEnum;
use WallaceMartinss\FilamentEvolution\Enums\MessageTypeEnum;
use WallaceMartinss\FilamentEvolution\Models\WhatsappInstance;
use WallaceMartinss\FilamentEvolution\Models\WhatsappMessage;

class SyncMessagesCommand extends Command
{
    protected $signature = 'evolution:sync-messages
                            {instance? : The instance name to sync messages for}
                            {--limit=1000 : Number of messages to fetch per request}
                            {--all : Sync messages for all instances}';

    protected $description = 'Sync historical messages from Evolution API to database';

    public function handle(): int
    {
        $instanceName = $this->argument('instance');
        $limit = (int) $this->option('limit');
        $syncAll = $this->option('all');

        if ($syncAll) {
            $instances = WhatsappInstance::where('sync_full_history', true)->get();
        } elseif ($instanceName) {
            $instances = WhatsappInstance::where('name', $instanceName)->get();
        } else {
            $this->error('Please specify an instance name or use --all flag');

            return Command::FAILURE;
        }

        if ($instances->isEmpty()) {
            $this->error('No instances found');

            return Command::FAILURE;
        }

        foreach ($instances as $instance) {
            $this->info("🔄 Syncing messages for instance: {$instance->name}");
            $this->syncInstanceMessages($instance, $limit);
        }

        return Command::SUCCESS;
    }

    private function syncInstanceMessages(WhatsappInstance $instance, int $limit): void
    {
        $apiKey = config('filament-evolution.api_key');
        $baseUrl = config('filament-evolution.base_url');

        if (! $apiKey || ! $baseUrl) {
            $this->error('Evolution API configuration missing');

            return;
        }

        $url = "{$baseUrl}/chat/findMessages/{$instance->instance_id}";
        $existingMessageIds = WhatsappMessage::where('instance_id', $instance->id)
            ->pluck('message_id')
            ->toArray();

        $this->info('📊 Found '.count($existingMessageIds).' existing messages');

        $offset = 0;
        $totalSynced = 0;

        do {
            try {
                /** @var \Illuminate\Http\Client\Response $response */
                $response = Http::withHeaders([
                    'apikey' => $apiKey,
                    'Content-Type' => 'application/json',
                ])->post($url, [
                    'limit' => $limit,
                    'offset' => $offset,
                ]);

                $status = $response->status();
                $bodyArray = json_decode($response->body() ?? '', true);
                if ($status < 200 || $status >= 300) {
                    $this->error('Failed to fetch messages: HTTP '.$status.' - '.(json_encode($bodyArray ?: '') ?: ''));
                    break;
                }

                $data = $bodyArray ?? [];
                $messages = $data['messages'] ?? [];

                if (empty($messages)) {
                    $this->info('✅ No more messages to sync');
                    break;
                }

                $newMessages = [];
                foreach ($messages as $message) {
                    $messageId = $message['id'];

                    if (in_array($messageId, $existingMessageIds)) {
                        continue; // Skip existing messages
                    }

                    $newMessages[] = $this->transformMessage($message, $instance);
                }

                if (! empty($newMessages)) {
                    WhatsappMessage::insert($newMessages);
                    $totalSynced += count($newMessages);
                    $this->info('✅ Synced '.count($newMessages)." new messages (offset: {$offset})");
                } else {
                    $this->info("⏭️  No new messages in this batch (offset: {$offset})");
                }

                $offset += $limit;

                // Safety check to prevent infinite loops
                if ($offset > 50000) {
                    $this->warn('⚠️  Reached maximum offset limit (50,000). Stopping sync.');
                    break;
                }

            } catch (Exception $e) {
                $this->error('Error syncing messages: '.$e->getMessage());
                break;
            }

        } while (count($messages) === $limit);

        $this->info("🎉 Total messages synced for {$instance->name}: {$totalSynced}");
    }

    private function transformMessage(array $message, WhatsappInstance $instance): array
    {
        $direction = $message['key']['fromMe'] ? MessageDirectionEnum::OUTGOING : MessageDirectionEnum::INCOMING;
        $messageType = $this->determineMessageType($message);
        $content = $this->extractContent($message);
        $media = $this->extractMedia($message);

        return [
            'instance_id' => $instance->id,
            'message_id' => $message['id'],
            'remote_jid' => $message['key']['remoteJid'],
            'phone' => $this->extractPhone($message['key']['remoteJid']),
            'direction' => $direction,
            'type' => $messageType,
            'content' => $content,
            'media' => $media,
            'status' => MessageStatusEnum::SENT, // Assume sent for historical messages
            'raw_payload' => $message,
            'sent_at' => now()->createFromTimestamp($message['messageTimestamp']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    private function determineMessageType(array $message): MessageTypeEnum
    {
        $messageType = $message['messageType'] ?? 'conversation';

        return match ($messageType) {
            'conversation' => MessageTypeEnum::TEXT,
            'imageMessage' => MessageTypeEnum::IMAGE,
            'audioMessage' => MessageTypeEnum::AUDIO,
            'videoMessage' => MessageTypeEnum::VIDEO,
            'documentMessage' => MessageTypeEnum::DOCUMENT,
            'locationMessage' => MessageTypeEnum::LOCATION,
            'contactMessage' => MessageTypeEnum::CONTACT,
            'stickerMessage' => MessageTypeEnum::STICKER,
            default => MessageTypeEnum::TEXT,
        };
    }

    private function extractContent(array $message): array
    {
        $content = [];

        if (isset($message['message']['conversation'])) {
            $content['text'] = $message['message']['conversation'];
        }

        // Add other content types as needed
        if (isset($message['message']['imageMessage'])) {
            $content['caption'] = $message['message']['imageMessage']['caption'] ?? null;
        }

        return $content;
    }

    private function extractMedia(array $message): ?array
    {
        // Extract media information if present
        if (isset($message['message']['imageMessage'])) {
            return [
                'url' => $message['message']['imageMessage']['url'] ?? null,
                'mimetype' => $message['message']['imageMessage']['mimetype'] ?? null,
                'fileLength' => $message['message']['imageMessage']['fileLength'] ?? null,
            ];
        }

        return null;
    }

    private function extractPhone(string $remoteJid): ?string
    {
        // Extract phone number from remoteJid (e.g., "1234567890@s.whatsapp.net" -> "1234567890")
        if (str_contains($remoteJid, '@')) {
            return explode('@', $remoteJid)[0];
        }

        return $remoteJid;
    }
}
