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
        Schema::create('tracer_studies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_alumni')->constrained('alumni')->onDelete('cascade');
            
            // Informasi Pribadi
            $table->string('nama');
            $table->string('no_hp', 20);
            $table->string('email');
            $table->year('tahun_lulus');
            $table->text('alamat');
            
            // Status Pekerjaan (1=Bekerja, 2=Belum bekerja, 3=Wiraswasta, 4=Pendidikan, 5=Mencari kerja)
            $table->enum('status_pekerjaan', ['1', '2', '3', '4', '5']);
            
            // Data Pekerjaan (untuk status 1)
            $table->string('nama_perusahaan')->nullable();
            $table->string('jabatan')->nullable();
            $table->text('alamat_pekerjaan')->nullable();
            $table->string('gaji', 100)->nullable();
            $table->enum('integritas', ['sangat_baik', 'baik', 'cukup', 'kurang_baik', 'tidak_baik'])->nullable();
            $table->enum('keahlian', ['sangat_baik', 'baik', 'cukup', 'kurang_baik', 'tidak_baik'])->nullable();
            $table->enum('kemampuan', ['sangat_baik', 'baik', 'cukup', 'kurang_baik', 'tidak_baik'])->nullable();
            $table->enum('penguasaan', ['sangat_baik', 'baik', 'cukup', 'kurang_baik', 'tidak_baik'])->nullable();
            $table->enum('komunikasi', ['sangat_baik', 'baik', 'cukup', 'kurang_baik', 'tidak_baik'])->nullable();
            $table->enum('kerja_tim', ['sangat_baik', 'baik', 'cukup', 'kurang_baik', 'tidak_baik'])->nullable();
            $table->enum('pengembangan', ['sangat_baik', 'baik', 'cukup', 'kurang_baik', 'tidak_baik'])->nullable();
            
            // Data Pencarian Kerja (untuk status 5)
            $table->string('cara_mencari_kerja')->nullable();
            $table->integer('jumlah_lamaran')->nullable();
            $table->integer('jumlah_panggilan')->nullable();
            
            // Data Belum Bekerja (untuk status 2)
            $table->string('alasan_tidak_bekerja')->nullable();
            $table->enum('rencana_cari_kerja', ['tidak', 'ya_ada_rencana'])->nullable();
            
            // Data Wiraswasta (untuk status 3)
            $table->string('nama_usaha')->nullable();
            $table->string('bidang_usaha')->nullable();
            $table->text('alamat_usaha')->nullable();
            
            // Data Pendidikan (untuk status 4)
            $table->string('nama_instansi')->nullable();
            $table->string('jurusan')->nullable();
            $table->string('jenjang', 100)->nullable();
            $table->year('tahun_masuk')->nullable();
            $table->text('alamat_instansi')->nullable();
            
            // Evaluasi Pendidikan
            $table->enum('relevansi_kurikulum', ['sangat_relevan', 'relevan', 'cukup', 'tidak_relevan', 'sangat_tidak_relevan']);
            $table->text('saran')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index(['id_alumni', 'tahun_lulus']);
            $table->index('status_pekerjaan');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tracer_studies');
    }
};