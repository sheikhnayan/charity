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
            // Based on current table structure, most columns already exist
            // Only add columns that are missing and needed
            
            if (!Schema::hasColumn('analytics_events', 'utm_term')) {
                $table->string('utm_term')->nullable()->after('utm_campaign');
            }
            
            if (!Schema::hasColumn('analytics_events', 'utm_content')) {
                $table->string('utm_content')->nullable()->after('utm_term');
            }
            
            if (!Schema::hasColumn('analytics_events', 'conversion_value')) {
                $table->decimal('conversion_value', 10, 2)->nullable()->after('conversion_data');
            }
            
            if (!Schema::hasColumn('analytics_events', 'os')) {
                $table->string('os')->nullable()->after('platform');
            }
            
            if (!Schema::hasColumn('analytics_events', 'duration')) {
                $table->integer('duration')->nullable()->after('city'); // Duration in seconds
            }
            
            if (!Schema::hasColumn('analytics_events', 'is_bounce')) {
                $table->boolean('is_bounce')->default(false)->after('duration');
            }
            
            if (!Schema::hasColumn('analytics_events', 'exit_page')) {
                $table->string('exit_page')->nullable()->after('landing_page');
            }
            
            if (!Schema::hasColumn('analytics_events', 'page_url')) {
                $table->string('page_url')->nullable()->after('url');
            }
            
            if (!Schema::hasColumn('analytics_events', 'user_agent')) {
                $table->string('user_agent')->nullable()->after('ip_address');
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
            
            if (Schema::hasColumn('analytics_events', 'utm_term')) {
                $columns[] = 'utm_term';
            }
            if (Schema::hasColumn('analytics_events', 'utm_content')) {
                $columns[] = 'utm_content';
            }
            if (Schema::hasColumn('analytics_events', 'conversion_value')) {
                $columns[] = 'conversion_value';
            }
            if (Schema::hasColumn('analytics_events', 'os')) {
                $columns[] = 'os';
            }
            if (Schema::hasColumn('analytics_events', 'duration')) {
                $columns[] = 'duration';
            }
            if (Schema::hasColumn('analytics_events', 'is_bounce')) {
                $columns[] = 'is_bounce';
            }
            if (Schema::hasColumn('analytics_events', 'exit_page')) {
                $columns[] = 'exit_page';
            }
            if (Schema::hasColumn('analytics_events', 'page_url')) {
                $columns[] = 'page_url';
            }
            if (Schema::hasColumn('analytics_events', 'user_agent')) {
                $columns[] = 'user_agent';
            }
            
            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};
