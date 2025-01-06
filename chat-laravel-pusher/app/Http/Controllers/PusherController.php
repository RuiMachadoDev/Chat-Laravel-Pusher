<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\PusherBroadcast;


class PusherController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function broadcast(Request $request)
    {
        $message = $request->input('message');
        $socket_id = $request->header('X-Socket-Id'); // Captura o socket_id do cabeçalho

        if (!$message) {
            \Log::error('Mensagem vazia recebida.');
            return response()->json(['error' => 'Message is empty'], 400);
        }

        \Log::info('Broadcasting mensagem:', [
            'message' => $message,
            'socket_id' => $socket_id,
        ]);

        // Passa o socket_id para o evento
        broadcast(new PusherBroadcast($message, $socket_id))->toOthers();

        return response()->json([
            'status' => 'Message broadcasted',
            'message' => $message,
        ]);
    }

    public function receive(Request $request)
    {
        return view('receive', ['message' => $request->get('message')]);
    }
}
