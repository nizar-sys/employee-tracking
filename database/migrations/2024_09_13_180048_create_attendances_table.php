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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->date('date');
            $table->time('time');
            $table->time('check_in');
            $table->time('check_out')->nullable();
            $table->string('location_check_in');
            $table->string('location_check_out')->nullable();
            $table->string('longlat_check_in');
            $table->string('longlat_check_out')->nullable();
            $table->boolean('is_valid_location_check_in');
            $table->boolean('is_valid_location_check_out')->nullable();
            $table->string('picture_check_in')->nullable();
            $table->string('picture_check_out')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
