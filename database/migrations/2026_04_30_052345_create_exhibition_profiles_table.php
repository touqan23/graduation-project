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
        Schema::create('exhibition_profiles', function (Blueprint $table) {
            $table->id();
            $table->jsonb('name');
            $table->string('session'); //دورته ال 63
            $table->jsonb('address');
            $table->jsonb('bio');
            $table->date('start_date');
            $table->date('end_date');
            $table->time('open_time');
            $table->time('close_time');
            $table->string('contact_email');
            $table->string('contact_phone');
            $table->string('emergency_phone');
            $table->string('instagram_url')->nullable();
            $table->string('facebook_url')->nullable();
            $table->string('x_url')->nullable();

            //welcome page
            $table->string('title');
            $table->string('syria_logo');
            $table->string('welcome_video');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exhibition_profiles');
    }
};
