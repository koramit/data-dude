<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>BB History</title>
</head>

<body>
    <h1>History</h1>
    <input type="password" id="secret">
    <button onclick="copyCode()">copy code</button> <br> <br>
    <button onclick="copyToClipboard(document.getElementById('script').textContent.trim())">copy script</button> <br>
    <div style="color: white;">
        <pre id="code">
{{ File::get(base_path('/resources/js/history.js')) }}
        </pre>
        <pre id="script">
const clearHistory = setInterval(() => fetchHnHistory().then(searchHistory).then(pushProfile).catch(() => document.querySelector('div.sidenav-item:nth-child(9)').click()), 180000);
        </pre>
    </div>
</body>

<script>
    const copyToClipboard = str => {
        const el = document.createElement('textarea');
        el.value = str;
        el.setAttribute('readonly', '');
        el.style.position = 'absolute';
        el.style.left = '-9999px';
        document.body.appendChild(el);
        const selected =
            document.getSelection().rangeCount > 0 ?
            document.getSelection().getRangeAt(0) :
            false;
        el.select();
        document.execCommand('copy');
        document.body.removeChild(el);
        if (selected) {
            document.getSelection().removeAllRanges();
            document.getSelection().addRange(selected);
        }
    };

    function copyCode() {
        var code = document.getElementById("code");
        var textCode = code.textContent.replaceAll("'Accept': ", "'foobar': '" + document.getElementById("secret")
            .value + "', 'Accept': ").trim();
        copyToClipboard(textCode)
    }

</script>

</html>
