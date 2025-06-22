<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLinecoreImageStorageTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('linecore_images', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('title', 255);
            $table->string('slug', 255);
            $table->text('file_folder');
            $table->text('file_source');
            $table->text('file_alt')->nullable();
            $table->text('file_description')->nullable();
            $table->text('description')->nullable();
            $table->text('file_exif')->nullable();
            $table->tinyInteger('is_active')->default(1);
            $table->string('checksum')->nullable()->default(null);
            $table->string('id_1c')->nullable()->default(null);
            $table->timestamps();
        });

        Schema::create('linecore_galleries', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('title', 255);
            $table->string('slug', 255);
            $table->timestamp('event_date');
            $table->tinyInteger('is_active')->default(1);
            $table->timestamps();
        });

        Schema::create('linecore_images2galleries', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('id_image')->unsigned();
            $table->integer('id_gallery')->unsigned();
            $table->tinyInteger('is_preview')->default(0);
            $table->tinyInteger('priority')->default(0);

            $table->foreign('id_image')->references('id')->on('linecore_images')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('id_gallery')->references('id')->on('linecore_galleries')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('linecore_tags', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('title', 255);
            $table->string('slug', 255);
            $table->tinyInteger('is_active')->default(1);
            $table->timestamps();
        });

        Schema::create('linecore_tags2entities', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('id_tag')->unsigned();
            $table->integer('id_entity')->unsigned();
            $table->string('entity_type', 64);

            $table->foreign('id_tag')->references('id')->on('linecore_tags')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('linecore_videos', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('id_preview')->unsigned()->nullable();
            $table->string('api_id', 255);
            $table->string('api_provider', 32);
            $table->string('title', 255);
            $table->string('slug', 255);
            $table->text('description');
            $table->tinyInteger('is_active')->default(1);
            $table->timestamps();

            $table->foreign('id_preview')->references('id')->on('linecore_images')->onDelete('set null')->onUpdate('set null');
        });

        Schema::create('linecore_video_galleries', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('title', 255);
            $table->string('slug', 255);
            $table->timestamp('event_date');
            $table->tinyInteger('is_active')->default(1);
            $table->timestamps();
        });

        Schema::create('linecore_videos2video_galleries', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('id_video')->unsigned();
            $table->integer('id_video_gallery')->unsigned();
            $table->tinyInteger('is_preview')->default(0);
            $table->tinyInteger('priority')->default(0);

            $table->foreign('id_video')->references('id')->on('linecore_videos')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('id_video_gallery')->references('id')->on('linecore_video_galleries')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('linecore_documents', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->text('file_folder');
            $table->text('file_source');
            $table->string('title', 255);
            $table->string('slug', 255);
            $table->tinyInteger('is_active')->default(1);
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
        Schema::dropIfExists('linecore_tags2entities');
        Schema::dropIfExists('linecore_images2galleries');
        Schema::dropIfExists('linecore_videos2video_galleries');
        Schema::dropIfExists('linecore_videos');
        Schema::dropIfExists('linecore_video_galleries');
        Schema::dropIfExists('linecore_documents');
        Schema::dropIfExists('linecore_images');
        Schema::dropIfExists('linecore_galleries');
        Schema::dropIfExists('linecore_tags');
    }
}
