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
        Schema::create('event_requests', function (Blueprint $table) {
            $table->id();

            $table->foreignId('slot_id')->constrained('event_slots')->onDelete('cascade');
            $table->foreignId('sector_id')->constrained('sectors');
            $table->foreignId('hall_id')->constrained('halls')->nullable();

            $table->string('organizer_name');
            $table->string('organizer_email');
            $table->string('organizer_phone');

            $table->jsonb('event_title');
            $table->jsonb('event_description');
            $table->string('Expected_attendance');
            $table->text('equipment_needed')->nullable();
            $table->string('image')->nullable(); //مسار صورة الفعالية
            $table->boolean('is_special')->default(false);

            $table->enum('request_status', ['pending', 'approved', 'rejected'
            ])->default('pending');

            // حالة الدفع
            $table->enum('payment_status', ['paid', 'unpaid', 'partial_paid'
            ])->default('unpaid');

            // تفاصيل الدفع
            $table->decimal('total_price', 10, 2)->nullable();
            $table->decimal('required_deposit', 10, 2)->nullable();
            $table->decimal('paid_amount', 10, 2)->default(0);
            $table->date('payment_due_date')->nullable();
            $table->timestamps();

            $table->index('payment_status'); // لتقارير المالية
            $table->index(['request_status', 'payment_status']); // لاستعلامات "المقبول ولم يدفع"
            $table->index(['sector_id', 'request_status']); // لفلترة القطاعات في لوحة التحكم
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_requests', function (Blueprint $table) {
            $table->dropIndex(['status', 'hall_id']);
            $table->dropIndex(['sector_id', 'status']);
        });
    }
};
