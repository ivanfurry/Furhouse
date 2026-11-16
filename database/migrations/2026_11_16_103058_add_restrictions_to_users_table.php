<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('can_post_products')->default(true)->after('is_active');
            $table->boolean('can_post_services')->default(true)->after('can_post_products');
            $table->boolean('can_create_events')->default(true)->after('can_post_services');
            $table->boolean('can_post_forum')->default(true)->after('can_create_events');
            $table->boolean('can_comment')->default(true)->after('can_post_forum');
            $table->text('restriction_reason')->nullable()->after('can_comment');
            $table->timestamp('restriction_until')->nullable()->after('restriction_reason');
            $table->foreignId('restricted_by')->nullable()->after('restriction_until')->constrained('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'can_post_products',
                'can_post_services',
                'can_create_events',
                'can_post_forum',
                'can_comment',
                'restriction_reason',
                'restriction_until',
                'restricted_by',
            ]);
        });
    }
};