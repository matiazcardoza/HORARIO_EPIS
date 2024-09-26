<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_curso');
            $table->enum('area_curricular', ['Estudios Generales', 'Estudios Específicos', 'Estudios de Especialidad']);
            $table->string('nombre_curso');
            $table->integer('ciclo');
            $table->enum('tipo_curso', ['Obligatorio', 'Electivo']);
            $table->string('grupo');
            $table->enum('turno', ['M', 'T']);
            $table->string('numero_aula');
            $table->integer('numero_estudiantes');
            $table->string('docente');
            $table->string('lunes')->nullable();
            $table->string('martes')->nullable();
            $table->string('miercoles')->nullable();
            $table->string('jueves')->nullable();
            $table->string('viernes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('courses');
    }
};
