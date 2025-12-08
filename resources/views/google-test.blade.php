<!DOCTYPE html>
<html>
<head>
    <title>Google OAuth Test</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="p-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-2xl font-bold mb-6">Google OAuth Test Flow</h1>
        
        <div class="space-y-6">
            <div class="p-4 border rounded-lg">
                <h2 class="font-bold mb-2">1. Generate OAuth URL</h2>
                <button onclick="generateUrl()" class="bg-blue-500 text-white px-4 py-2 rounded">
                    Generate Google OAuth URL
                </button>
                <div id="url-container" class="mt-2 hidden">
                    <p class="text-sm text-gray-600 mb-1">Generated URL:</p>
                    <div class="bg-gray-100 p-2 rounded break-all" id="generated-url"></div>
                    <a href="#" id="oauth-link" target="_blank" class="text-blue-500 mt-2 inline-block">
                        Click to test OAuth
                    </a>
                </div>
            </div>
            
            <div class="p-4 border rounded-lg">
                <h2 class="font-bold mb-2">2. Direct Test Links</h2>
                <div class="space-y-2">
                    <a href="{{ route('google.login') }}" 
                       class="inline-block bg-green-500 text-white px-4 py-2 rounded">
                        Test via Route (google.login)
                    </a>
                    <p class="text-sm text-gray-600">Route: {{ route('google.login') }}</p>
                </div>
            </div>
            
            <div class="p-4 border rounded-lg">
                <h2 class="font-bold mb-2">3. Check Current Status</h2>
                <div id="status" class="text-gray-600">
                    Loading...
                </div>
                <button onclick="checkStatus()" class="bg-gray-500 text-white px-4 py-2 rounded mt-2">
                    Refresh Status
                </button>
            </div>
        </div>
    </div>
    
    <script>
    function generateUrl() {
        fetch('/test-google-oauth-json')
            .then(response => response.json())
            .then(data => {
                document.getElementById('generated-url').textContent = data.url;
                document.getElementById('oauth-link').href = data.url;
                document.getElementById('url-container').classList.remove('hidden');
            });
    }
    
    function checkStatus() {
        document.getElementById('status').innerHTML = 'Checking...';
        
        fetch('/check-google-status')
            .then(response => response.json())
            .then(data => {
                let html = '<div class="space-y-2">';
                html += `<div><strong>Config Loaded:</strong> ${data.config_loaded ? '✅' : '❌'}</div>`;
                html += `<div><strong>Client ID:</strong> ${data.client_id ? '✅' : '❌'}</div>`;
                html += `<div><strong>Redirect URI:</strong> ${data.redirect_uri}</div>`;
                html += `<div><strong>Recent Logs:</strong> ${data.recent_logs || 'None'}</div>`;
                html += '</div>';
                
                document.getElementById('status').innerHTML = html;
            });
    }
    
    // Check status on page load
    checkStatus();
    </script>
</body>
</html>