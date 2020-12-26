<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dude</title>
</head>

<body>
    <h1>Run Dude</h1>
    @csrf
    <input type="number" placeholder="start" id="input_start">
    <input type="number" placeholder="stop" id="input_stop">
    <button
        onclick="runDudeSync(document.getElementById('input_start').value, document.getElementById('input_stop').value);">run</button>
</body>

</html>
