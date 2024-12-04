<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateFilesTable extends Migration
{
    public function up(): void
    {
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->string('path');
            $table->string('type');
            $table->binary('image');
            $table->binary('image64');
            $table->timestamps(); // Agrega las columnas created_at y updated_at
        });

        // Cambiar el tipo de las columnas image y image64 a longblob
        DB::statement('ALTER TABLE files CHANGE image image LONGBLOB');
        DB::statement('ALTER TABLE files CHANGE image64 image64 LONGBLOB');
    }

    public function down(): void
    {
        Schema::dropIfExists('files');
    }
}