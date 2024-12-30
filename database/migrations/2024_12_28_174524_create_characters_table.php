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
        Schema::create('characters', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('camp_id')->unsigned()->nullable();
            $table->foreign('camp_id')->references('id')->on('campaigns');
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->bigInteger('race_id')->unsigned();
            $table->foreign('race_id')->references('id')->on('races');
            $table->bigInteger('charclass_id')->unsigned();
            $table->foreign('charclass_id')->references('id')->on('charclasses');
            $table->string('name');
            $table->json('atributes');
            $table->json('spell_list')->nullable();
            $table->json('equipment_list')->nullable();
            $table->text('description');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('characters');
    }
};
