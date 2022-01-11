<?php
namespace App\Services;
use App\Models\{ Spider, Summary };
use App\Helpers\{ SpiderHelper, SummaryHelper };

/**
 * Service class that handles the business logic for the CrawlController. This 
 * encompasses initiating the chosen action (Custom vs Random), inserting new 
 * values into the database tables, and then returning the summary of the crawl.
 *
 */
class CrawlService {
    /**
     * Handles the Custom Crawl action which given the URL, will 
     * crawl the given URL along with 3-5 adjacent internal links. 
     * 
     * @param string $url
     * @return Summary
     */
    public function customCrawl($url) {
        $spiderHelper = new SpiderHelper("https://www.agencyanalytics.com/" . $url);

        // fetch the found internal links, then randomly select 3-5 of them
        $links = json_decode($spiderHelper->unique_internal_links, true);
        shuffle($links);
        $pages = array_slice($links, 0, rand(3,5));

        // traverse the links array and add the baseurl to each value
        // array_walk($pages, function(&$value, $key) { $value = "https://www.agencyanalytics.com" . $value; });

        // add result of first page to collection, it counts for the page count
        $spiders = collect();
        $spiders->add(Spider::create(
            array(
                "url"                   => $spiderHelper->url,
                "status_code"           => $spiderHelper->status_code,
                "unique_images"         => $spiderHelper->unique_images,
                "unique_internal_links" => $spiderHelper->unique_internal_links,
                "unique_external_links" => $spiderHelper->unique_external_links,
                "page_load_time"        => $spiderHelper->page_load_time,
                "word_count"            => $spiderHelper->word_count,
                "title_length"          => $spiderHelper->title_length
            )
        ));

        return $this->executeCrawl($pages, $spiders);
    }
    
    /**
     * Handles the Random Crawl action, which takes the agencyanalytics sitemap and
     * crawls 4-6 randomly selected links from it. 
     * 
     * @return Summary
     */
    public function randomCrawl() {
        // use simplexml to read sitemap, and turn it into an assoc array
        $xml = json_decode(json_encode(simplexml_load_file("https://agencyanalytics.com/sitemap.xml")), true);

        // fetch the found internal links, then randomly select 4-6 of them while isolating the URL found in 'loc'
        $links = $xml["url"];
        shuffle($links);
        $pages = array_column(array_slice($links, 0, rand(4,6)), "loc");
        
        return $this->executeCrawl($pages, collect());
    }

    /**
     * Delegates the crawl to the SpiderHelper while adding results from it 
     * to the Spider database table. Passes spiders to the createSummary function 
     * to handle Summary creation.
     * 
     * @param \Illuminate\Support\Collection $spiders
     * @return Summary
     */
    private function executeCrawl($pages, $spiders) {
        // iterate through each page, creating a spider and adding a corresponding record
        foreach ($pages as $page) {
            $spiderHelper = new SpiderHelper($page);
            
            $spiders->add(Spider::create(
                array(
                    "url"                   => $spiderHelper->url,
                    "status_code"           => $spiderHelper->status_code,
                    "unique_images"         => $spiderHelper->unique_images,
                    "unique_internal_links" => $spiderHelper->unique_internal_links,
                    "unique_external_links" => $spiderHelper->unique_external_links,
                    "page_load_time"        => $spiderHelper->page_load_time,
                    "word_count"            => $spiderHelper->word_count,
                    "title_length"          => $spiderHelper->title_length
                )
            ));
        }

        return $this->createSummary($spiders);
    }

    /**
     * Delegates result handling to SummaryHelper which is used to create a Summary 
     * database record. Result is returned to be consumed by a corresponding view.
     * 
     * @param \Illuminate\Support\Collection $spiders
     * @return Summary
     */
    private function createSummary($spiders) {
        // map the spiders to a summary, then create a new summary record
        $summaryHelper = new SummaryHelper($spiders);
        $summary = Summary::create(
            array(
                "page_count"                 => $summaryHelper->page_count,
                "unique_image_count"         => $summaryHelper->unique_image_count,
                "unique_internal_link_count" => $summaryHelper->unique_internal_link_count,
                "unique_external_link_count" => $summaryHelper->unique_external_link_count,
                "average_page_load_time"     => $summaryHelper->average_page_load_time,
                "average_word_count"         => $summaryHelper->average_word_count,
                "average_title_length"       => $summaryHelper->average_title_length
            )
        );

        // add records to the pivot table to connect summary to spiders
        $summary->spiders()->attach($spiders->pluck("id")->toArray());
        return $summary;
    }
}