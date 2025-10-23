
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('rekomendasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('makanans_id')->constrained('makanan')->onDelete('cascade');
            $table->decimal('skor', 8, 4);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rekomendasi');
    }
};
