<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('bookings', 'user_id')) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->foreignId('user_id')->nullable()->after('id')->constrained()->nullOnDelete();
            });
        }

        if (! Schema::hasColumn('seat_reservations', 'booking_id')) {
            Schema::table('seat_reservations', function (Blueprint $table) {
                $table->foreignId('booking_id')->nullable()->after('user_id')->constrained('bookings')->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('seat_reservations', 'booking_id')) {
            Schema::table('seat_reservations', function (Blueprint $table) {
                $table->dropConstrainedForeignId('booking_id');
            });
        }

        if (Schema::hasColumn('bookings', 'user_id')) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->dropConstrainedForeignId('user_id');
            });
        }
    }
};
