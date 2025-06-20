<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class MigrateVisToLinecoreData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Migrate data from old vis_ tables to new linecore_ tables
        // Only if old tables exist
        
        if (Schema::hasTable('vis_images') && Schema::hasTable('linecore_images')) {
            DB::statement('INSERT INTO linecore_images SELECT * FROM vis_images');
        }
        
        if (Schema::hasTable('vis_galleries') && Schema::hasTable('linecore_galleries')) {
            DB::statement('INSERT INTO linecore_galleries SELECT * FROM vis_galleries');
        }
        
        if (Schema::hasTable('vis_images2galleries') && Schema::hasTable('linecore_images2galleries')) {
            DB::statement('INSERT INTO linecore_images2galleries SELECT * FROM vis_images2galleries');
        }
        
        if (Schema::hasTable('vis_tags') && Schema::hasTable('linecore_tags')) {
            DB::statement('INSERT INTO linecore_tags SELECT * FROM vis_tags');
        }
        
        if (Schema::hasTable('vis_tags2entities') && Schema::hasTable('linecore_tags2entities')) {
            DB::statement('INSERT INTO linecore_tags2entities SELECT * FROM vis_tags2entities');
        }
        
        if (Schema::hasTable('vis_videos') && Schema::hasTable('linecore_videos')) {
            DB::statement('INSERT INTO linecore_videos SELECT * FROM vis_videos');
        }
        
        if (Schema::hasTable('vis_video_galleries') && Schema::hasTable('linecore_video_galleries')) {
            DB::statement('INSERT INTO linecore_video_galleries SELECT * FROM vis_video_galleries');
        }
        
        if (Schema::hasTable('vis_videos2video_galleries') && Schema::hasTable('linecore_videos2video_galleries')) {
            DB::statement('INSERT INTO linecore_videos2video_galleries SELECT * FROM vis_videos2video_galleries');
        }
        
        if (Schema::hasTable('vis_documents') && Schema::hasTable('linecore_documents')) {
            DB::statement('INSERT INTO linecore_documents SELECT * FROM vis_documents');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Clear new tables
        if (Schema::hasTable('linecore_tags2entities')) {
            DB::table('linecore_tags2entities')->truncate();
        }
        
        if (Schema::hasTable('linecore_images2galleries')) {
            DB::table('linecore_images2galleries')->truncate();
        }
        
        if (Schema::hasTable('linecore_videos2video_galleries')) {
            DB::table('linecore_videos2video_galleries')->truncate();
        }
        
        if (Schema::hasTable('linecore_videos')) {
            DB::table('linecore_videos')->truncate();
        }
        
        if (Schema::hasTable('linecore_video_galleries')) {
            DB::table('linecore_video_galleries')->truncate();
        }
        
        if (Schema::hasTable('linecore_documents')) {
            DB::table('linecore_documents')->truncate();
        }
        
        if (Schema::hasTable('linecore_images')) {
            DB::table('linecore_images')->truncate();
        }
        
        if (Schema::hasTable('linecore_galleries')) {
            DB::table('linecore_galleries')->truncate();
        }
        
        if (Schema::hasTable('linecore_tags')) {
            DB::table('linecore_tags')->truncate();
        }
    }
}