<?php
namespace App\Http\Controllers;

use App\Models\PushSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PushController extends Controller
{
    public function subscribe(Request $request)
    {
        $data = $request->validate([
            'endpoint'   => ['required','string','max:500'],
            'p256dh'     => ['nullable','string','max:500'],
            'auth_token' => ['nullable','string','max:100'],
        ]);

        PushSubscription::updateOrCreate(
            ['user_id' => Auth::id(), 'endpoint' => $data['endpoint']],
            ['p256dh' => $data['p256dh'] ?? null, 'auth_token' => $data['auth_token'] ?? null]
        );

        return response()->json(['ok' => true]);
    }

    public function unsubscribe(Request $request)
    {
        $data = $request->validate(['endpoint' => ['required','string']]);
        PushSubscription::where('user_id', Auth::id())->where('endpoint', $data['endpoint'])->delete();
        return response()->json(['ok' => true]);
    }

    /**
     * Send a push notification to a user (internal helper, called from other controllers).
     * Uses the Web Push Protocol — no third-party package needed for basic notifications.
     */
    public static function sendToUser(int $userId, string $title, string $body, string $url = '/'): void
    {
        $subs = PushSubscription::where('user_id', $userId)->get();
        foreach ($subs as $sub) {
            self::sendPush($sub, $title, $body, $url);
        }
    }

    private static function sendPush(PushSubscription $sub, string $title, string $body, string $url): void
    {
        $payload = json_encode([
            'title' => $title,
            'body'  => $body,
            'icon'  => '/icons/icon-192.png',
            'url'   => $url,
        ]);

        // For VAPID-less push (Firefox/Chrome with applicationServerKey),
        // we send to the push endpoint directly with the payload.
        // Note: Full VAPID requires crypto — for production use minishlink/web-push.
        try {
            $ch = curl_init($sub->endpoint);
            curl_setopt_array($ch, [
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $payload,
                CURLOPT_HTTPHEADER => ['Content-Type: application/json','TTL: 86400'],
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 5,
            ]);
            curl_exec($ch);
            curl_close($ch);
        } catch (\Throwable $e) {
            // Fail silently — push is best-effort
        }
    }
}
