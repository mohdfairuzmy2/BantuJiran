<?php
namespace App\Console\Commands;

use App\Models\Post;
use Illuminate\Console\Command;

class CloseExpiredPosts extends Command
{
    protected $signature   = 'posts:close-expired';
    protected $description = 'Auto-close posts past their expires_at timestamp';

    public function handle(): void
    {
        $count = Post::whereIn('status',['open','in_progress'])
            ->whereNotNull('expires_at')
            ->where('expires_at','<', now())
            ->update(['status' => 'expired']);

        $this->info("Closed $count expired posts.");
    }
}
