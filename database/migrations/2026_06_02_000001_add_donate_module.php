<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Remove leftover posts from the retired "monitoring" module so the
        // type enum can be tightened without violating existing rows.
        DB::table('posts')->where('type', 'monitoring')->delete();

        // Add the new "donate" module type to the posts enum.
        DB::statement("ALTER TABLE posts MODIFY type ENUM('sos','donate','tool','mobility','group_buy') NOT NULL");

        // Allow the matching response kind for donate requests.
        DB::statement("ALTER TABLE responses MODIFY kind ENUM('help_offer','group_buy_join','carpool_request','borrow_request','item_request') NOT NULL");
    }

    public function down(): void
    {
        DB::table('posts')->where('type', 'donate')->delete();
        DB::statement("ALTER TABLE posts MODIFY type ENUM('sos','group_buy','mobility','tool','monitoring') NOT NULL");
        DB::table('responses')->where('kind', 'item_request')->delete();
        DB::statement("ALTER TABLE responses MODIFY kind ENUM('help_offer','group_buy_join','carpool_request','borrow_request') NOT NULL");
    }
};
