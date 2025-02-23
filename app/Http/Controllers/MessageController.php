<?php

namespace App\Http\Controllers;

use App\Events\NewMessage;
use App\Models\Chat;
use App\Models\Message;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\ChatResource;
use App\Http\Requests\Api\StoreMessageRequest;
use App\Http\Requests\Api\FirstMessageRequest;

class MessageController extends Controller
{

    protected string $defaultSortField = 'created_at';
    protected string $defaultSortOrder = 'desc';

    /**
     * Send first message
     * 
     * @param FirstMessageRequest $request
     * @return JsonResponse
     */
    public function firstMessage(FirstMessageRequest $request)
    {
        $user = auth()->user();
        if ($chat = Chat::whereIn('user_1_id', [$user->id, $request->to_user_id])->whereIn('user_2_id', [$user->id, $request->to_user_id])->first()) {
            return $this->success(
                null,
                'يوجد محادثة بينك وبين هذا المستخدم من قبل',
                200,
                ['chat_id' => $chat->id]
            );
        }

        try {
            $validated = $request->validated();
            DB::beginTransaction();
            $chat = Chat::create([
                'user_1_id' => $user->id,
                'user_2_id' => $validated['to_user_id'],
                'last_message_at' => null,
            ]);
            $message = new Message();
            DB::commit();
            broadcast(new NewMessage($chat, $message))->toOthers();
            return $this->success(new ChatResource($chat), 'تم انشاء محادثة جديدة');
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error($e->getMessage());
            return $this->handleException($e);
        }
    }

    /**
     * Store message
     * 
     * @param StoreMessageRequest $request
     * @return JsonResponse
     */
    public function store(StoreMessageRequest $request)
    {
        $chat = Chat::findOrFail($request->chat_id);
        $validated = $request->validated();
        $validated['attachments']   = Message::handleAttachments($request->attachments);
        $validated['user_id']       = auth()->user()->id;
        if (!in_array(auth()->user()->id, [$chat->user_1_id, $chat->user_2_id])) {
            return $this->error('ليس لديك الصلاحية لإرسال الرسائل في هذه المحادثة', 403);
        }

        try {
            $message = $chat->messages()->create($validated);
            broadcast(new NewMessage($chat, $message))->toOthers();
            return $this->success($message, 'تم إرسال الرسالة بنجاح');
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }
}
