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
        const pusher = new Pusher('{{config('broadcasting.connections.pusher.key')}}', {cluster: 'eu'});
        const channel = pusher.subscribe('public');

        // Receber mensagens
        channel.bind('chat', function (data) {
            console.log("Mensagem recebida:", data.message);

            // Adiciona a mensagem recebida na interface
            $(".messages").append(`
                <div class="left message">
                    <img src="https://assets.adlin.app/images/rossedlin/03/rossedlin-03-100.jpg" alt="Avatar">
                    <p>${data.message}</p>
                </div>
            `);

            // Rolagem automática para o fim da conversa
            $(document).scrollTop($(document).height());
        });


        // Broadcast mensagens
        $("form").submit(function (event) {
            event.preventDefault();

            $.ajax({
                url: "/broadcast",
                method: "POST",
                headers: {
                    'X-Socket-Id': pusher.connection.socket_id
                },
                data: {
                    _token: '{{csrf_token()}}',
                    message: $("form #message").val(),
                }
            }).done(function (res) {
                $(".messages > .message").last().after(res);
                $("form #message").val('');
                $(document).scrollTop($(document).height());
            });
        });
    </script>
</html>
