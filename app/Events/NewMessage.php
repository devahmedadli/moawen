<?php

namespace App\Events;

use App\Models\Chat;
use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use App\Http\Resources\AttachmentResource;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;

class NewMessage implements ShouldBroadcastNow, ShouldDispatchAfterCommit
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Chat $chat;
    public Message $message;
    /**
     * Create a new event instance.
     */
    public function __construct(Chat $chat, Message $message)
    {
        $this->chat = $chat;
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('chats.' . $this->chat->id),
        ];
    }

    /**
     * Determine if this event should broadcast.
     *
     * @return bool
     */
    public function broadcastWhen(): bool
    {
        return !$this->message->seen;
    }


    /**
     * The event's broadcast data.
     */
    public function broadcastWith(): array
    {
        return [
            'chat_id'       => $this->message->chat_id,
            'id'            => $this->message->id,
            'body'          => $this->message->body,
            'seen'          => $this->message->seen,
            'user_id'       => $this->message->user_id,
            'attachments'   => AttachmentResource::collection($this->message->attachments),
            'created_at'    => $this->message->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
