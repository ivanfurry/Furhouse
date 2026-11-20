<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('support_tickets', function (Blueprint $table) {
            $table->foreignId('service_id')->nullable()->after('assigned_to')->constrained('services')->onDelete('cascade');
            $table->enum('type', ['general', 'service_discussion'])->default('general')->after('service_id');
        });
    }

    public function down(): void
    {
        Schema::table('support_tickets', function (Blueprint $table) {
            $table->dropColumn(['service_id', 'type']);
        });
    }
};