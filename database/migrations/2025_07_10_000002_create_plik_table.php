<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('plik', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('konkurs_id')->constrained('konkurs')->onDelete('cascade');
            $table->string('path');
            $table->string('original_name');
            $table->integer('size');
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('plik');
    }
}; 