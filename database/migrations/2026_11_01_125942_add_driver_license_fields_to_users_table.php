<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('license_full_name')->nullable();
            $table->string('license_number')->nullable()->unique();
            $table->string('license_code')->nullable();
            $table->date('license_expiration')->nullable();
            $table->string('contact_number')->nullable();
            $table->string('license_status')->default('not_submitted');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['license_number']);

            $table->dropColumn([
                'license_full_name',
                'license_number',
                'license_code',
                'license_expiration',
                'contact_number',
                'license_status',
            ]);
        });
    }
};