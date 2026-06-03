<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['sos', 'donate', 'tool', 'mobility', 'group_buy']);
            $table->string('title');
            $table->text('body')->nullable();
            $table->string('category')->nullable();
            $table->enum('status', ['open', 'in_progress', 'completed', 'closed', 'expired'])->default('open');
            $table->enum('severity', ['low', 'medium', 'high', 'critical'])->nullable(); // SOS
            $table->decimal('price', 10, 2)->nullable(); // micro-economy
            $table->decimal('lat', 10, 7);
            $table->decimal('lng', 10, 7);
            $table->decimal('radius_km', 4, 1)->default(3);
            $table->json('meta')->nullable(); // seats, origin/destination, target_qty, tool name, watch window
            $table->foreignId('helper_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->index(['type', 'status']);
        });

        // Generated spatial column (SRID 4326) derived from lat/lng, with a spatial index
        // for fast ST_Distance_Sphere radius matching (BRD geospatial requirement).
        DB::statement('ALTER TABLE posts ADD COLUMN location POINT GENERATED ALWAYS AS (ST_SRID(POINT(lng, lat), 4326)) STORED NOT NULL');
        DB::statement('ALTER TABLE posts ADD SPATIAL INDEX posts_location_spatialindex (location)');
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
