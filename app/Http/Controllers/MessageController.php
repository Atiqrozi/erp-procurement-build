<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Division;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $messages = Message::with(['senderDivision', 'targetDivision'])
            ->where('target_division_id', $user->division_id)
            ->orderByDesc('created_at')
            ->get();

        return view('messages.index', compact('messages'));
    }

    public function create()
    {
        $divisions = Division::all();
        return view('messages.create', compact('divisions'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'message' => 'required|string|max:550',
            'status' => 'nullable|string',
            'target_division_id' => 'required|exists:divisions,id',
        ]);

        Message::create([
            'message' => $data['message'],
            'status' => $data['status'] ?? null,
            'target_division_id' => $data['target_division_id'],
            'sender_division_id' => auth()->user()->division_id,
        ]);

        return redirect()->route('messages.index')
            ->with('success', 'Pesan berhasil dikirim.');
    }

    public function markAsRead(Message $message)
    {
        $this->authorize('markAsRead', $message);

        $message->update(['is_read' => true]);

        return redirect()->route('messages.index')->with('success', 'Pesan ditandai sudah dibaca.');
    }


}
