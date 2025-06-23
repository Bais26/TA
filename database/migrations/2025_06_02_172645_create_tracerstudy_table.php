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
        Schema::create('tracerstudy', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_alumni')->unsigned()->nullable();
            $table->foreign('id_alumni')->references('id')->on('alumni')->onDelete('cascade');
            $table->date('tanggal_isi');   
            $table->string('bekerja');
            $table->string('nama_perusahaan')->nullable();
            $table->string('jabatan')->nullable();
            $table->string('alamat_pekerjaan')->nullable();     
            $table->string('status_kerja'); 
            $table->string('relevansi_pekerjaan')->nullable(); 
            $table->string('pekerjaan')->nullable();       
            $table->decimal('gaji', 10, 2)->nullable();  
            $table->string('saran')->nullable();          
            $table->timestamps();     
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tracerstudy', function (Blueprint $table) {
            $table->dropForeign('tracerstudy_id_alumni_foreign');
        });
        
        Schema::dropIfExists('tracerstudy');
    }
};