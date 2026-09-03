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
        // 1. Rename tickets table
        Schema::rename('tickets', 'service_requests');

        // 2. Add new fields to service_requests
        Schema::table('service_requests', function (Blueprint $table) {
            // We use integer instead of foreignId for rooms because rooms table might not exist yet!
            // The prompt said: "Phase 3: Rooms". We are currently in Phase 2.
            // But the instructions explicitly say: Add room_id, asset_id.
            // If rooms table doesn't exist, we can't add constrained().
            $table->unsignedBigInteger('room_id')->nullable();
            $table->foreignId('asset_id')->nullable()->constrained('equipment')->nullOnDelete();
        });

        // 3. Rename comments table and update foreign key
        Schema::rename('ticket_comments', 'service_request_comments');
        Schema::table('service_request_comments', function (Blueprint $table) {
            $table->renameColumn('ticket_id', 'service_request_id');
        });

        // 4. Rename attachments table and update foreign key
        Schema::rename('ticket_attachments', 'service_request_attachments');
        Schema::table('service_request_attachments', function (Blueprint $table) {
            $table->renameColumn('ticket_id', 'service_request_id');
        });

        // 5. Rename activity logs table and update foreign key
        Schema::rename('ticket_activity_logs', 'service_request_activity_logs');
        Schema::table('service_request_activity_logs', function (Blueprint $table) {
            $table->renameColumn('ticket_id', 'service_request_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse activity logs
        Schema::table('service_request_activity_logs', function (Blueprint $table) {
            $table->renameColumn('service_request_id', 'ticket_id');
        });
        Schema::rename('service_request_activity_logs', 'ticket_activity_logs');

        // Reverse attachments
        Schema::table('service_request_attachments', function (Blueprint $table) {
            $table->renameColumn('service_request_id', 'ticket_id');
        });
        Schema::rename('service_request_attachments', 'ticket_attachments');

        // Reverse comments
        Schema::table('service_request_comments', function (Blueprint $table) {
            $table->renameColumn('service_request_id', 'ticket_id');
        });
        Schema::rename('service_request_comments', 'ticket_comments');

        // Remove added fields
        Schema::table('service_requests', function (Blueprint $table) {
            $table->dropForeign(['asset_id']);
            $table->dropColumn(['room_id', 'asset_id']);
        });

        // Rename back to tickets
        Schema::rename('service_requests', 'tickets');
    }
};
