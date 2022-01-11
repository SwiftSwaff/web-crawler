<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

/**
 * Summary model representation. Encapsulates laid out requirements for displaying results.
 *
 */
class Summary extends Model {
    public $timestamps = false;
    public $fillable = [
        "page_count",
        "unique_image_count", 
        "unique_internal_link_count", 
        "unique_external_link_count", 
        "average_page_load_time",
        "average_word_count",
        "average_title_length"
    ];

    /**
     * Maps summary to its respective spiders in a pivot table.
     * 
     * @return void
     */
    public function spiders() {
        return $this->belongsToMany(Spider::class);
    }
}