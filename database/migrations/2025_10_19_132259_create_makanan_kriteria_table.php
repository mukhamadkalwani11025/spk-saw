<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('makanan_kriteria', function (Blueprint $table) {
            $table->id();
            $table->foreignId('makanans_id')->constrained('makanan')->onDelete('cascade');
            $table->foreignId('kriterias_id')->constrained('kriteria')->onDelete('cascade');
            $table->decimal('nilai', 8, 2);
            $table->timestamps();

            $table->unique(['makanans_id', 'kriterias_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('makanan_kriteria');
    }
};
