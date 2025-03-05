<?php

namespace App\Http\Controllers\Api\Freelancer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Chat;
use App\Http\Resources\ChatResource;

class ChatController extends Controller
{
    protected string $defaultSortField = 'last_message_at';
    protected string $defaultSortOrder = 'desc';

    /**
     * Display a listing of the resource.
     * 
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {

            $query = Chat::where('user_1_id', auth()->user()->id)->orWhere('user_2_id', auth()->user()->id);

            $query = $this->buildQuery($request, $query);

            $chats = $query->paginate($request->get('per_page', 100));

            return $this->success(
                ChatResource::collection($chats),
                'تم جلب المحادثات بنجاح',
            );
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Display the specified resource.
     * 
     * @param string $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $chat = Chat::with('messages')->findOrFail($id);
        if (!$chat) {

            return $this->error('المحادثة غير موجودة', 404);
        }
        if (!in_array(auth()->user()->id, [$chat->user_1_id, $chat->user_2_id])) {
            return $this->error('ليس لديك الصلاحية لإرسال الرسائل في هذه المحادثة', 403);
        }
        return $this->success(new ChatResource($chat), 'تم جلب المحادثة بنجاح');
    }
}
