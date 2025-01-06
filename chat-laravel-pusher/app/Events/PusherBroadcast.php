<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PusherBroadcast implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    // Declaração da propriedade `message`
    public $message;
    public $socket_id;

    /**
     * Construtor para inicializar a mensagem.
     *
     * @param string $message
     */
    public function __construct(string $message, ?string $socket_id)
    {
        $this->message = $message; // Inicializa a propriedade
        $this->socket_id = $socket_id;
        \Log::info('Evento disparado:', ['message' => $message]);
    }

    /**
     * Define os canais nos quais o evento será transmitido.
     */
    public function broadcastOn(): array
    {
        return [new Channel('public')];
    }

    /**
     * Adiciona dados adicionais ao evento.
     */
    public function broadcastWith(): array
    {
        return [
            'message' => $this->message, // Garante que a mensagem será enviada no payload
            'socket_id' => $this->socket_id,
        ];
    }

    /**
     * Define o nome do evento no canal.
     */
    public function broadcastAs(): string
    {
        return 'chat';
    }
}