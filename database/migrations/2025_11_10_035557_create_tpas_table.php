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
        Schema::create('tpas', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
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
            $table->string('lat')->nullable();
            $table->string('long')->nullable();
            $table->string('sumber')->nullable();
            $table->string('tahun_konstruksi')->nullable();
            $table->string('tahun_beroperasi')->nullable();
            $table->integer('rencana')->default(0);
            $table->decimal('luas_sarana', 12, 1)->default(0);
            $table->decimal('luas_sel', 12, 1)->default(0);
            $table->string('pengelola')->nullable();
            $table->string('pengelola_desc')->nullable();
            $table->string('kondisi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tpas');
    }
};
