<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {

            // ── Status: add no_show if not already present, keep existing values ──
            // We expand the enum to include the new statuses.
            // MySQL requires re-declaring the full enum list when altering.
            DB::statement("
                ALTER TABLE bookings
                MODIFY COLUMN status ENUM(
                    'pending',
                    'confirmed',
                    'completed',
                    'cancelled',
                    'no_show'
                ) NOT NULL DEFAULT 'pending'
            ");

            // ── Who cancelled and what kind of cancellation ────────────────────
            // cancelled_by  : customer | shop | system (scheduler)
            // cancellation_type : early | late | no_show
            $table->string('cancelled_by')->nullable()->after('status');
            $table->string('cancellation_type')->nullable()->after('cancelled_by');

            // grace period tracking — scheduler sets this when it fires
            $table->timestamp('no_show_detected_at')->nullable()->after('cancellation_type');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['cancelled_by', 'cancellation_type', 'no_show_detected_at']);

            // Revert the status enum to original values
            DB::statement("
                ALTER TABLE bookings
                MODIFY COLUMN status ENUM(
                    'pending',
                    'confirmed',
                    'completed',
                    'cancelled'
                ) NOT NULL DEFAULT 'pending'
            ");
        });
    }
};
