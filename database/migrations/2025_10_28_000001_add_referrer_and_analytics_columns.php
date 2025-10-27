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
            // Add referrer_url column if missing (needed for referrer analytics query)
            if (!Schema::hasColumn('analytics_events', 'referrer_url')) {
                $table->string('referrer_url')->nullable();
            }
            
            // Add referrer column if missing
            if (!Schema::hasColumn('analytics_events', 'referrer')) {
                $table->string('referrer')->nullable();
            }
            
            // Add other commonly needed analytics columns that might be missing
            if (!Schema::hasColumn('analytics_events', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable();
            }
            
            if (!Schema::hasColumn('analytics_events', 'session_id')) {
                $table->string('session_id')->nullable();
            }
            
            if (!Schema::hasColumn('analytics_events', 'ip_address')) {
                $table->string('ip_address')->nullable();
            }
            
            if (!Schema::hasColumn('analytics_events', 'user_agent')) {
                $table->text('user_agent')->nullable();
            }
            
            if (!Schema::hasColumn('analytics_events', 'device_type')) {
                $table->string('device_type')->nullable();
            }
            
            if (!Schema::hasColumn('analytics_events', 'browser')) {
                $table->string('browser')->nullable();
            }
            
            if (!Schema::hasColumn('analytics_events', 'country')) {
                $table->string('country')->nullable();
            }
            
            if (!Schema::hasColumn('analytics_events', 'city')) {
                $table->string('city')->nullable();
            }

            // Add indexes for better query performance
            $table->index(['referrer_url', 'website_id', 'created_at'], 'idx_referrer_analytics');
            $table->index(['url', 'website_id', 'created_at'], 'idx_url_analytics');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('analytics_events', function (Blueprint $table) {
            // Drop indexes first
            $table->dropIndex('idx_referrer_analytics');
            $table->dropIndex('idx_url_analytics');
            
            // Remove columns if they exist
            $columnsToRemove = [
                'referrer_url',
                'referrer',
                'user_id',
                'session_id',
                'ip_address',
                'user_agent',
                'device_type',
                'browser',
                'country',
                'city'
            ];
            
            foreach ($columnsToRemove as $column) {
                if (Schema::hasColumn('analytics_events', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};