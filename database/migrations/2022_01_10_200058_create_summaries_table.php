<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSummariesTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create("summaries", function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger("page_count", 0);
            $table->unsignedInteger("unique_image_count", 0);
            $table->unsignedInteger("unique_internal_link_count", 0);
            $table->unsignedInteger("unique_external_link_count", 0);
            $table->float("average_page_load_time", 0.0);
            $table->float("average_word_count", 0.0);
            $table->float("average_title_length", 0.0);
        });

        Schema::create("spider_summary", function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("spider_id");
            $table->unsignedBigInteger("summary_id");
            $table->foreign("spider_id")->references("id")->on("spiders")->onDelete("cascade")->onUpdate('cascade');
            $table->foreign("summary_id")->references("id")->on("summaries")->onDelete("cascade")->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists("spider_summary");
        Schema::dropIfExists("summaries");
    }
}
