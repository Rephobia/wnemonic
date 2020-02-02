<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Literal;

class CreateTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tags', function (Blueprint $table)
        {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->string(Literal::tagField())->unique();
        });

        Schema::create('file_tags', function (Blueprint $table)
        {
            $table->timestamps();
            $table->bigInteger('file_id');
            $table->foreign('file_id')->references('id')->on('files')->onDelete('cascade');

            $table->bigInteger('tags_id');
            $table->foreign('tags_id')->references('id')->on('tags')->onDelete('cascade');
 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('file_tags');
        Schema::dropIfExists('tags');
    }
}
