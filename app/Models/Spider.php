<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

/**
 * Spider model representation. Encapsulates laid out requirements.
 *
 */
class Spider extends Model {
    public $timestamps = false;
    public $fillable = [
        "url",
        "status_code", 
        "unique_images", 
        "unique_internal_links", 
        "unique_external_links", 
        "page_load_time",
        "word_count",
        "title_length"
    ];

    /**
     * Maps spiders to a summary in a pivot table.
     * 
     * @return void
     */
    public function summary() {
        return $this->belongsTo(Summary::class);
    }
}