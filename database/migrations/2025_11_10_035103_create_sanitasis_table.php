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
        Schema::create('sanitasis', function (Blueprint $table) {
            $table->id();
            $table->string('tahun');
            $table->text('nama');
            $table->string('lokasi')->nullable();
            $table->bigInteger('pagu')->default(0);
            $table->bigInteger('jumlah')->default(0);
            $table->string('sumber')->nullable();
            $table->string('lat')->nullable();
            $table->string('long')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sanitasis');
    }
};
