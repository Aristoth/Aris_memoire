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
        Schema::create('document_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_doc');
            $table->unsignedBigInteger('id_user');
            $table->foreign('id_user')->references('id')->on('user')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('id_doc')->references('id')->on('documents')->onUpdate('cascade')->onDelete('cascade');
            $table->string('info_doc');
            $table->string('info_updated');
            $table->string('details')->references('')
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_user');
    }
};
