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
            $table->string('name')->nullable();
            $table->boolean('medicine');
            $table->string('recheck')->nullable();
            $table->string('dx')->nullable();
            $table->string('counter', 20)->nullable();
            $table->string('remark')->nullable();
            $table->timestamp('encountered_at')->nullable();
            $table->timestamp('dismissed_at')->nullable();
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
