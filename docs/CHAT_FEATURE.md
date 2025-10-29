# Chat Feature Documentation

## Overview

The chat feature has been successfully integrated into the People Of Data platform using the Chatify package. This provides users with a real-time messaging system to communicate with each other.

## Features

- **One-on-One Messaging**: Users can send direct messages to other users
- **Message Attachments**: Support for sending files and images
- **Real-time Updates**: Messages appear instantly (when configured with broadcasting)
- **Favorites**: Mark important conversations as favorites
- **Search**: Search through conversations and messages
- **Online Status**: See when users are online
- **Typing Indicators**: See when someone is typing
- **Message Seen Status**: Know when messages have been read

## Integration Points

### 1. Navigation
A messages icon has been added to the main navigation bar (desktop and mobile) for easy access to the chat interface.

**Location**: Top navigation bar, next to the notifications icon

### 2. Profile Pages
Each user profile now displays a "Message" button that allows other users to instantly start a conversation with them.

**Location**: User profile page → Action buttons section

**Behavior**: Clicking the message button redirects to the chat page with that specific user's conversation opened.

### 3. Chat Page
The main chat interface is accessible at `/chatify`

**Features**:
- Left sidebar: List of all conversations
- Main area: Message thread with the selected user
- Right sidebar: User information and shared media

## Technical Details

### Routes
All chat routes are prefixed with `/chatify`:
- `GET /chatify` - Main chat interface
- `GET /chatify/{user_id}` - Open chat with specific user
- `POST /chatify/api/sendMessage` - Send a message
- `POST /chatify/api/fetchMessages` - Fetch message history
- And many more API endpoints for various chat features

### Database Tables
Two main tables support the chat feature:
- `ch_messages` - Stores all chat messages
- `ch_favorites` - Stores favorited conversations

### Models
- `App\Models\ChMessage` - Message model
- `App\Models\ChFavorite` - Favorite conversation model
- `App\Models\User` - Extended with chat capabilities

## Usage

### For End Users

#### Starting a Chat
1. **From Profile**: 
   - Visit any user's profile
   - Click the "Message" button
   - Start typing your message

2. **From Chat Page**:
   - Click the messages icon in the navigation
   - Search for a user
   - Click on their name to start a conversation

#### Sending Messages
1. Type your message in the text area at the bottom
2. Press Enter or click the send button
3. To attach files, click the attachment icon

#### Managing Conversations
- **Star/Unstar**: Click the star icon to add/remove from favorites
- **Delete**: Click the menu icon → Delete conversation
- **Search**: Use the search bar to find specific conversations or messages

### For Developers

#### Opening a Chat Programmatically
```php
// Generate URL to chat with a specific user
$chatUrl = url(config('chatify.routes.prefix') . '/' . $user->id);

// Or in Blade templates
<a href="{{ url(config('chatify.routes.prefix') . '/' . $user->id) }}">
    Chat with {{ $user->name }}
</a>
```

#### Querying Messages
```php
use App\Models\ChMessage;

// Get all messages between two users
$messages = ChMessage::where(function($query) use ($userId1, $userId2) {
        $query->where('from_id', $userId1)->where('to_id', $userId2);
    })
    ->orWhere(function($query) use ($userId1, $userId2) {
        $query->where('from_id', $userId2)->where('to_id', $userId1);
    })
    ->orderBy('created_at', 'desc')
    ->get();
```

## Configuration

The chat feature can be configured in `config/chatify.php`:

```php
return [
    'name' => env('CHATIFY_NAME', 'Chatify Messenger'),
    'storage_disk_name' => env('CHATIFY_STORAGE_DISK', 'public'),
    'routes' => [
        'prefix' => env('CHATIFY_ROUTES_PREFIX', 'chatify'),
        'middleware' => ['web', 'auth'],
    ],
    // ... more configuration options
];
```

### Environment Variables
Add these to your `.env` file for customization:
```env
CHATIFY_NAME="People Of Data Chat"
CHATIFY_STORAGE_DISK=public
CHATIFY_ROUTES_PREFIX=chatify
CHATIFY_MAX_FILE_SIZE=150
```

## Real-time Features (Optional)

To enable real-time messaging, you need to configure Laravel Broadcasting:

1. Set up a broadcasting driver (Pusher, Redis, etc.)
2. Configure in `.env`:
```env
BROADCAST_DRIVER=pusher
PUSHER_APP_ID=your-app-id
PUSHER_APP_KEY=your-app-key
PUSHER_APP_SECRET=your-app-secret
PUSHER_APP_CLUSTER=mt1
```

3. Uncomment broadcasting configuration in `config/chatify.php`

## Testing

Run the chat integration tests:
```bash
php artisan test --filter=ChatifyIntegrationTest
```

## Troubleshooting

### Chat not loading
- Ensure you're logged in
- Check browser console for JavaScript errors
- Verify routes are registered: `php artisan route:list --name=chatify`

### Messages not sending
- Check file upload permissions on `storage/app/public`
- Verify database connection
- Check Laravel logs: `storage/logs/laravel.log`

### Attachments not working
- Run: `php artisan storage:link`
- Check `config/chatify.php` for allowed file types
- Verify `CHATIFY_MAX_FILE_SIZE` in `.env`

## Security

- All chat routes require authentication (`auth` middleware)
- File uploads are validated for type and size
- Messages are stored securely in the database
- Only conversation participants can access messages

## Future Enhancements

Potential improvements for the chat feature:
- [ ] Group messaging
- [ ] Video/voice calls
- [ ] Message reactions
- [ ] Message editing/deletion
- [ ] Push notifications
- [ ] Message encryption
- [ ] File preview before sending
- [ ] GIF support
- [ ] Read receipts

## Support

For issues related to Chatify:
- Package Documentation: https://github.com/munafio/chatify
- Project Issues: Create an issue in the project repository

## Credits

- Chatify Package: [munafio/chatify](https://github.com/munafio/chatify)
- Integration: People Of Data Development Team

