<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Chat-Laravel</title>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
        <script src="https://js.pusher.com/7.2/pusher.min.js"></script>

        <link rel="stylesheet" href="/style.css">
    </head>
    <body>
        <div class="chat">
            <div class="top">
                <img src="https://assets.adlin.app/images/rossedlin/03/rossedlin-03-100.jpg" alt="Avatar">
                <div>
                    <p>Ross Edlin</p>
                    <small>Online</small>
                </div>
            </div>
            <div class="messages">
                @include('receive', ['message' => "Hey! What's up?"])
            </div>
            <div class="bottom">
                <form>
                    <input type="text" id="message" name="message" placeholder="Enter message..." autocomplete="off">
                    <button type="submit"></button>
                </form>
            </div>
        </div>
    </body>

    <script>
        // Inicialização do Pusher
        const pusher = new Pusher('{{ config('broadcasting.connections.pusher.key') }}', {
            cluster: 'eu',
            forceTLS: true,
        });

        // Assinar o canal público
        const channel = pusher.subscribe('public');

        // Enviar mensagem
        $("form").submit(function (event) {
            event.preventDefault();

            const message = $("form #message").val();

            if (!message) {
                alert("Mensagem não pode estar vazia!");
                return;
            }

            // Adiciona a mensagem localmente (lado direito)
            $(".messages").append(`
                <div class="right message">
                    <p>${message}</p>
                    <img src="https://via.placeholder.com/100" alt="Avatar">
                </div>
            `);

            // Envia a mensagem para o backend
            $.ajax({
                url: "/broadcast",
                method: "POST",
                headers: {
                    'X-Socket-Id': pusher.connection.socket_id,
                },
                data: {
                    _token: '{{ csrf_token() }}',
                    message: message,
                }
            }).fail(function (err) {
                console.error("Erro ao enviar mensagem:", err.responseText);
            });
        });

        // Receber mensagens via broadcast
        channel.bind('chat', function (data) {
            console.log("Mensagem recebida no broadcast:", data);
            console.log("Socket ID local:", pusher.connection.socket_id);

            // Ignora mensagens enviadas pelo próprio remetente
            if (data.socket_id === pusher.connection.socket_id) {
                console.log("Mensagem ignorada (do próprio remetente)");
                return;
            }

            // Adiciona mensagens recebidas no lado esquerdo
            $(".messages").append(`
                <div class="left message">
                    <img src="https://via.placeholder.com/100" alt="Avatar">
                    <p>${data.message}</p>
                </div>
            `);

            $(document).scrollTop($(document).height());
        });

    </script>
</html>
