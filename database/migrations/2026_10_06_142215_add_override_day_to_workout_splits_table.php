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
        Schema::table('workout_splits', function (Blueprint $table) {
            $table->foreignId('override_day_id')->nullable()->after('current_position')
                ->constrained('workout_days')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('workout_splits', function (Blueprint $table) {
            $table->dropConstrainedForeignId('override_day_id');
        });
    }
};
