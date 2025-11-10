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
        Schema::create('iplts', function (Blueprint $table) {
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
            $table->string('lat')->nullable();
            $table->string('long')->nullable();
            $table->string('tahun_konstruksi')->nullable();
            $table->integer('terpasang')->default(0);
            $table->integer('terpakai')->default(0);
            $table->integer('tidak_terpakai')->default(0);
            $table->integer('truk')->default(0);
            $table->integer('kapasitas_truk')->default(0);
            $table->string('kondisi_truk')->nullable();
            $table->integer('rit')->default(0);
            $table->integer('pemanfaat_kk')->default(0);
            $table->integer('pemanfaat_jiwa')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('iplts');
    }
};
