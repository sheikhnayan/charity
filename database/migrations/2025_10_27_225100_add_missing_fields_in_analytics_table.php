<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('analytics_events', function (Blueprint $table) {
            $table->string('user_id')->nullable();
            $table->string('referrer_url')->nullable();
            $table->json('meta_data')->nullable();
            $table->string('method')->nullable();
            $table->string('platform')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('analytics_events', function (Blueprint $table) {
            //
        });
    }
};
