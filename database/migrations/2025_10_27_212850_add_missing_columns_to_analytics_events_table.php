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
            // Add only the columns that are actually missing
            // Handle different table structures between local and VPS
            
            // Add conversion_data if missing (this is the main column causing the original error)
            if (!Schema::hasColumn('analytics_events', 'conversion_data')) {
                $table->json('conversion_data')->nullable();
            }
            
            // Add event_data if missing
            if (!Schema::hasColumn('analytics_events', 'event_data')) {
                $table->json('event_data')->nullable();
            }
            
            // Add UTM columns if missing (without relying on specific column order)
            if (!Schema::hasColumn('analytics_events', 'utm_source')) {
                $table->string('utm_source')->nullable();
            }
            
            if (!Schema::hasColumn('analytics_events', 'utm_medium')) {
                $table->string('utm_medium')->nullable();
            }
            
            if (!Schema::hasColumn('analytics_events', 'utm_campaign')) {
                $table->string('utm_campaign')->nullable();
            }
            
            if (!Schema::hasColumn('analytics_events', 'utm_term')) {
                $table->string('utm_term')->nullable();
            }
            
            if (!Schema::hasColumn('analytics_events', 'utm_content')) {
                $table->string('utm_content')->nullable();
            }
            
            // Add conversion_value if missing
            if (!Schema::hasColumn('analytics_events', 'conversion_value')) {
                $table->decimal('conversion_value', 10, 2)->nullable();
            }
            
            // Add device and browser info if missing
            if (!Schema::hasColumn('analytics_events', 'device_type')) {
                $table->string('device_type')->nullable();
            }
            
            if (!Schema::hasColumn('analytics_events', 'browser')) {
                $table->string('browser')->nullable();
            }
            
            if (!Schema::hasColumn('analytics_events', 'os')) {
                $table->string('os')->nullable();
            }
            
            // Add location info if missing
            if (!Schema::hasColumn('analytics_events', 'country')) {
                $table->string('country')->nullable();
            }
            
            if (!Schema::hasColumn('analytics_events', 'city')) {
                $table->string('city')->nullable();
            }
            
            // Add analytics metrics if missing
            if (!Schema::hasColumn('analytics_events', 'duration')) {
                $table->integer('duration')->nullable(); // Duration in seconds
            }
            
            if (!Schema::hasColumn('analytics_events', 'is_bounce')) {
                $table->boolean('is_bounce')->default(false);
            }
            
            // Add page tracking if missing
            if (!Schema::hasColumn('analytics_events', 'landing_page')) {
                $table->string('landing_page')->nullable();
            }
            
            if (!Schema::hasColumn('analytics_events', 'exit_page')) {
                $table->string('exit_page')->nullable();
            }
            
            if (!Schema::hasColumn('analytics_events', 'page_url')) {
                $table->string('page_url')->nullable();
            }
            
            if (!Schema::hasColumn('analytics_events', 'user_agent')) {
                $table->string('user_agent')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('analytics_events', function (Blueprint $table) {
            // Drop only the columns we actually added
            $columns = [];
            
            // Check and collect all potentially added columns
            $columnsToCheck = [
                'conversion_data',
                'event_data',
                'utm_source',
                'utm_medium',
                'utm_campaign',
                'utm_term',
                'utm_content',
                'conversion_value',
                'device_type',
                'browser',
                'os',
                'country',
                'city',
                'duration',
                'is_bounce',
                'landing_page',
                'exit_page',
                'page_url',
                'user_agent'
            ];
            
            foreach ($columnsToCheck as $column) {
                if (Schema::hasColumn('analytics_events', $column)) {
                    $columns[] = $column;
                }
            }
            
            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};
