<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Chat-Laravel</title>

        <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
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
</html>
