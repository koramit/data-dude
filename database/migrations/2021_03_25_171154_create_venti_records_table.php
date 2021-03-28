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
            $table->string('no', 20)->index()->nullable();
            $table->string('bed', 20)->nullable();
            $table->string('hn', 20)->index()->nullable();
            $table->string('name')->index()->nullable();
            $table->boolean('medicine')->index();
            $table->string('cc')->index()->nullable();
            $table->string('dx')->index()->nullable();
            $table->string('movement')->index()->nullable();
            $table->string('counter', 20)->index()->nullable();
            $table->string('insurance')->index()->nullable();
            $table->string('outcome')->index()->nullable();
            $table->string('remark')->nullable();
            $table->timestamp('encountered_at')->index()->nullable();
            $table->timestamp('dismissed_at')->index()->nullable();
            $table->timestamp('tagged_med_at')->index()->nullable();
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
