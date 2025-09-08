<!DOCTYPE html>
<html>
<head>
    <title>Get Google Token</title>
    <script src="https://accounts.google.com/gsi/client" async defer></script>
</head>
<body>
    <div id="g_id_onload"
         data-client_id="{{ config('services.google.client_id') }}"
         data-callback="handleCredentialResponse">
    </div>
    <div class="g_id_signin" data-type="standard"></div>

    <script>
        function handleCredentialResponse(response) {
            console.log("Encoded JWT ID token: " + response.credential);
            document.getElementById('token').innerHTML = response.credential;
            
            // Auto copy to clipboard
            navigator.clipboard.writeText(response.credential).then(function() {
                alert('Token copied to clipboard!');
            });
        }
    </script>
    
    <div style="margin: 20px;">
        <h3>Google Client ID:</h3>
        <p><code>{{ config('services.google.client_id') }}</code></p>
        
        <h3>Token:</h3>
        <textarea id="token" style="width: 100%; height: 200px; word-wrap: break-word;" readonly></textarea>
        
        <br><br>
        <button onclick="copyToken()">Copy Token</button>
    </div>

    <script>
        function copyToken() {
            const token = document.getElementById('token').value;
            if (token) {
                navigator.clipboard.writeText(token).then(function() {
                    alert('Token copied to clipboard!');
                });
            } else {
                alert('No token to copy');
            }
        }
    </script>
</body>
</html>