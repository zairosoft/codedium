<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('attachments', function (Blueprint $table) {
            $table->id();
            $table->string('attachable_type'); // Model class name
            $table->unsignedBigInteger('attachable_id'); // Model ID
            $table->string('file_name'); // Original file name
            $table->string('file_path'); // Storage path
            $table->unsignedBigInteger('file_size')->nullable(); // File size in bytes
            $table->string('mime_type')->nullable(); // MIME type
            $table->unsignedBigInteger('uploaded_by')->nullable(); // User who uploaded
            $table->timestamps();

            // Indexes
            $table->index(['attachable_type', 'attachable_id']);
            $table->index(['uploaded_by']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attachments');
    }
};
