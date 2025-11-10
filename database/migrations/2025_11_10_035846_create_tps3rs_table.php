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
        Schema::create('tps3rs', function (Blueprint $table) {
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
            $table->integer('luas')->default(0);
            $table->string('tahun_konstruksi')->nullable();
            $table->string('tahun_beroperasi')->nullable();
            $table->decimal('jumlah_timbunan', 12, 2)->default(0);
            $table->integer('jumlah_penduduk')->default(0);
            $table->integer('jumlah_kk')->default(0);
            $table->integer('gerobak')->default(0);
            $table->integer('motor')->default(0);
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tps3rs');
    }
};
