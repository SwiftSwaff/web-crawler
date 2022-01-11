<?php
namespace App\Helpers;

/**
 * Helper class responsible for representing the summary of data obtained from
 * the SpiderHelper class, to be consumed by the Summary Model.
 * 
 */
class SummaryHelper {
    public function __construct(
        \Illuminate\Support\Collection $spiders,
        public int $page_count = 0,
        public int $unique_image_count = 0,
        public int $unique_internal_link_count = 0,
        public int $unique_external_link_count = 0,
        public float $average_page_load_time = 0.0,
        public float $average_word_count = 0.0,
        public float $average_title_length = 0.0
    ) {
        $links = array(
            "internal" => array(),
            "external" => array()
        );
        $images = array();

        $load_time_total = $word_total = $title_total = 0;
        foreach ($spiders as $spider) {
            $this->page_count++;

            // merge all images and links as we iterate, will remove collective duplicates after
            $images            = array_merge($images, json_decode($spider->unique_images, true));
            $links["internal"] = array_merge($links["internal"], json_decode($spider->unique_internal_links, true));
            $links["external"] = array_merge($links["external"], json_decode($spider->unique_external_links, true));

            $load_time_total  += $spider->page_load_time;
            $word_total       += $spider->word_count;
            $title_total      += $spider->title_length;
        }

        // obtain totals from unique entries
        $this->unique_image_count         = count(array_unique($images));
        $this->unique_internal_link_count = count(array_unique($links["internal"]));
        $this->unique_external_link_count = count(array_unique($links["external"]));

        // rounding to 3 decimal places to avoid massive decimal values
        $this->average_page_load_time = round(($load_time_total / $this->page_count), 3);
        $this->average_word_count     = round(($word_total / $this->page_count), 3);
        $this->average_title_length   = round(($title_total / $this->page_count), 3);
    }
}