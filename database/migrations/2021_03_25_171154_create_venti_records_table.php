<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVentiRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('venti_records', function (Blueprint $table) {
            $table->id();
            $table->string('no', 20)->unique(); // er-queue
            $table->string('en', 20)->index()->nullable(); // profile
            $table->string('location')->nullable(); // profile
            $table->string('hn', 20)->index()->nullable(); // er-queue
            $table->string('name')->index()->nullable(); // er-queue
            $table->boolean('medicine')->index(); // er-queue
            $table->string('cc')->index()->nullable(); // profile
            $table->string('dx')->index()->nullable(); // profile
            $table->string('triage', 512)->nullable(); // profile
            $table->string('counter', 20)->index()->nullable(); // er-queue
            $table->string('insurance')->index()->nullable(); // profile
            $table->string('outcome')->index()->nullable(); // history
            $table->string('vital_signs')->nullable(); // profile
            $table->string('remark')->nullable(); // er-queue
            $table->timestamp('encountered_at')->index()->nullable(); // er-queue
            $table->timestamp('dismissed_at')->index()->nullable(); // er-queue
            $table->timestamp('tagged_med_at')->index()->nullable(); // er-queue
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
        Schema::dropIfExists('venti_records');
    }
}
