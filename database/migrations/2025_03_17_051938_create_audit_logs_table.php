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
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->string('action'); // e.g., created, updated, deleted
            $table->string('model'); // e.g., Invoice, Product
            $table->unsignedBigInteger('model_id'); // ID of the affected model
            $table->text('changes')->nullable(); // JSON or text representation of changes
            $table->unsignedBigInteger('user_id')->nullable(); // ID of the user who performed the action
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
