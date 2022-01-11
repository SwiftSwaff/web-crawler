<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpidersTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create("spiders", function (Blueprint $table) {
            $table->id();
            $table->string("url", 128);
            $table->unsignedInteger("status_code", 0);
            $table->json("unique_images", "");
            $table->json("unique_internal_links", "");
            $table->json("unique_external_links", "");
            $table->float("page_load_time", 0.0);
            $table->unsignedInteger("word_count", 0);
            $table->unsignedInteger("title_length", 0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists("spiders");
    }
}
