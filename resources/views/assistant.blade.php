<!DOCTYPE html>
<html>
<head>
    <title>OpenAI Assistant</title>
</head>
<body>
    @php
    $decodedMessages = json_decode($messages, true);
    @endphp

    @if(is_array($decodedMessages))
        @for ($i = 0; $i < count($decodedMessages); $i += 2)
            <p><strong>User:</strong> {{ $decodedMessages[$i]['content'] }}</p>
            @if (isset($decodedMessages[$i + 1]))
                <p><strong>Van-Ons Assistant:</strong> {{ $decodedMessages[$i + 1]['content'] }}</p>
            @endif
        @endfor
    @else
        <p>Error: Messages are not in the correct format.</p>
    @endif

    <form action="/submit-message" method="post">
        @csrf
        <label for="message">Enter your message:</label><br>
        <input type="text" id="message" name="message"><br>
        <input type="submit" value="Submit">
    </form>
</body>
</html>