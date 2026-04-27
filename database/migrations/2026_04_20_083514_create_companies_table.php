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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('company_request_id')->constrained()->onDelete('restrict');
            $table->string('name');
            $table->string('logo')->nullable(); // مسار اللوغو
            $table->string('responsible_person');
            $table->string('sector');
            $table->text('bio')->nullable();
            $table->text('address');
            $table->float('final_area');
            $table->string('booth_type'); // 'Equipped Booth', etc.
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
