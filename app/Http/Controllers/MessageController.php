<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    // Phương thức gửi tin nhắn
    public function sendMessage(Request $request)
    {
        $request->validate([
            'job_id' => 'required|integer',
            'receiver_id' => 'required|integer',
            'content' => 'required|string',
        ]);

        $message = Message::create([
            'job_id' => $request->job_id,
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'content' => $request->content,
        ]);

        return response()->json(['success' => true, 'message' => $message]);
    }

    // Phương thức lấy danh sách tin nhắn
    public function getMessages($jobId)
    {
        $messages = Message::where('job_id', $jobId)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($messages);
    }
}
