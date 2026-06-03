<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users','is_admin'))     $table->boolean('is_admin')->default(false)->after('is_verified');
            if (!Schema::hasColumn('users','onboarded_at')) $table->timestamp('onboarded_at')->nullable()->after('is_admin');
            if (!Schema::hasColumn('users','is_banned'))    $table->boolean('is_banned')->default(false)->after('onboarded_at');
        });
    }
    public function down(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(array_filter(['is_admin','onboarded_at','is_banned'], fn($c) => Schema::hasColumn('users',$c)));
        });
    }
};
