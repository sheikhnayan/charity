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
        Schema::table('dealmaker_config', function (Blueprint $table) {
            // Add background color fields for regulation cards (using smaller varchar for colors)
            $table->string('reg_cf_bg_color', 10)->nullable()->default('#1F2937');
            $table->string('reg_cf_bold_text_color', 10)->nullable()->default('#14B8A6');
            $table->string('reg_cf_text_color', 10)->nullable()->default('#FFFFFF');
            
            $table->string('reg_a_bg_color', 10)->nullable()->default('#1F2937');
            $table->string('reg_a_bold_text_color', 10)->nullable()->default('#14B8A6');
            $table->string('reg_a_text_color', 10)->nullable()->default('#FFFFFF');
            
            $table->string('reg_d_bg_color', 10)->nullable()->default('#1F2937');
            $table->string('reg_d_bold_text_color', 10)->nullable()->default('#14B8A6');
            $table->string('reg_d_text_color', 10)->nullable()->default('#FFFFFF');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dealmaker_config', function (Blueprint $table) {
            $table->dropColumn([
                'reg_cf_bg_color', 'reg_cf_bold_text_color', 'reg_cf_text_color',
                'reg_a_bg_color', 'reg_a_bold_text_color', 'reg_a_text_color',
                'reg_d_bg_color', 'reg_d_bold_text_color', 'reg_d_text_color'
            ]);
        });
    }
};
