<!DOCTYPE html>
<html>
<head>
    <title>PHP Info Check</title>
    <style>
        body { font-family: monospace; background: #111; color: #eee; }
        .ok { color: #4caf50; }
        .bad { color: #f44336; }
        iframe { width: 100%; height: 80vh; border: none; background: #fff; }
    </style>
</head>
<body>

<h2>Guzzle Requirements Check</h2>

<p>
    cURL extension:
    <strong class="{{ $curl_loaded ? 'ok' : 'bad' }}">
        {{ $curl_loaded ? 'ENABLED' : 'DISABLED' }}
    </strong>
</p>

<p>
    curl_init():
    <strong class="{{ $curl_init ? 'ok' : 'bad' }}">
        {{ $curl_init ? 'AVAILABLE' : 'MISSING' }}
    </strong>
</p>

<p>
    curl_exec():
    <strong class="{{ $curl_exec ? 'ok' : 'bad' }}">
        {{ $curl_exec ? 'AVAILABLE' : 'MISSING' }}
    </strong>
</p>

<p>
    allow_url_fopen:
    <strong class="{{ $allow_url_fopen ? 'ok' : 'bad' }}">
        {{ $allow_url_fopen ? 'ON' : 'OFF' }}
    </strong>
</p>

<hr>

<h3>Full phpinfo()</h3>
<iframe srcdoc="{!! $phpinfo !!}"></iframe>

</body>
</html>
