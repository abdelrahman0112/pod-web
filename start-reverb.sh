#!/bin/bash

# Laravel Reverb WebSocket Server Startup Script
# This script starts the Laravel Reverb WebSocket server for real-time chat functionality

echo "🚀 Starting Laravel Reverb WebSocket Server..."
echo "📡 Server will be available at: http://127.0.0.1:8080"
echo "🔑 App Key: local-key"
echo "🔐 App Secret: local-secret"
echo "🆔 App ID: local-app-id"
echo ""
echo "Press Ctrl+C to stop the server"
echo ""

# Start Laravel Reverb
php artisan reverb:start --host=127.0.0.1 --port=8080
