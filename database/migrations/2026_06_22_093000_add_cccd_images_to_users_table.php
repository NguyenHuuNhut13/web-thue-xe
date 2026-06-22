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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'cccd_front')) {
                $table->string('cccd_front')->nullable();
            }
            if (!Schema::hasColumn('users', 'cccd_back')) {
                $table->string('cccd_back')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'cccd_front')) {
                $table->dropColumn('cccd_front');
            }
            if (Schema::hasColumn('users', 'cccd_back')) {
                $table->dropColumn('cccd_back');
            }
        });
    }
};
