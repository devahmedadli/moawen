<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Broadcasting\Channel;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;

class GeneralNotification extends Notification
{
    use Queueable;

    protected $data;
    /**
     * Create a new notification instance.
     */
    public function __construct(array $data)
    {
        $this->data = $data;
        $this->afterCommit();
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    public function toDatabase($notifiable): array
    {
        try {
            return [
                'type' => get_class($this),
                'notifiable_type' => get_class($notifiable),
                'notifiable_id' => $notifiable->id,
                'data' => [
                    'title' => $this->data['title'],
                    'message' => $this->data['message'],
                    'type' => $this->data['type'] ?? 'info',
                    'action_url' => $this->data['action_url'] ?? null,
                    'model_id' => $this->data['model_id'] ?? null,
                    'model_type' => $this->data['model_type'] ?? null,
                    'created_at' => now()->toISOString(),
                    'image' => $this->data['image'] ?? asset('assets/img/logo/logo.png'),
                ],
                'read_at' => null,
            ];
        } catch (\Exception $e) {
            Log::error('Error in GeneralNotification::toDatabase: ' . $e->getMessage());
            return [];
        }
    }

    public function toBroadcast($notifiable): BroadcastMessage
    {
        try {
            return new BroadcastMessage([
                'id' => $this->id,
                'type' => get_class($this),
                'notifiable_id' => $notifiable->id,
                'notifiable_type' => get_class($notifiable),
                'data' => [
                    'title' => $this->data['title'],
                    'message' => $this->data['message'],
                    'type' => $this->data['type'] ?? 'info',
                    'time' => now()->diffForHumans(),
                    'action_url' => $this->data['action_url'] ?? null,
                    'image' => $this->data['image'] ?? asset('assets/img/logo/logo.png'),
                ],
                'read_at' => null,
                'created_at' => now()->toISOString(),
            ]);
        } catch (\Exception $e) {
            Log::error('Error in GeneralNotification::toBroadcast: ' . $e->getMessage());
            return new BroadcastMessage([]);
        }
    }


    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }

    
    public function broadcastOn(): Channel
    {
        return new Channel('notifications');
    }

    public function broadcastAs(): string
    {
        return 'new-notification';
    }
}
