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
        Schema::create('spalds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kecamatan_id')
                ->nullable()
                ->constrained('kecamatans')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->foreignId('kelurahan_id')
                ->nullable()
                ->constrained('kelurahans')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->string('nama');
            $table->string('alamat')->nullable();
            $table->string('lat')->nullable();
            $table->string('long')->nullable();
            $table->string('skala')->nullable();
            $table->string('tahun_konstruksi')->nullable();
            $table->string('sumber')->nullable();
            $table->string('status_keberfungsian')->nullable();
            $table->string('kondisi')->nullable();
            $table->string('status_lahan')->nullable();
            $table->decimal('kapasitas', 12, 1)->default(0);
            $table->string('jenis')->nullable();
            $table->string('teknologi')->nullable();
            $table->integer('pemanfaat_jiwa')->default(0);
            $table->integer('rumah_terlayani')->default(0);
            $table->integer('unit_tangki')->default(0);
            $table->integer('unit_bilik')->default(0);
            $table->string('status_penyedotan')->nullable();
            $table->date('tanggal_update')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spalds');
    }
};
