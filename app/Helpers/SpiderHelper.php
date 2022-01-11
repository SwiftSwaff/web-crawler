<?php
namespace App\Helpers;

/**
 * Helper class responsible for performing crawling and returning requirement values
 * that mirror the Spider Model.
 *
 */
class SpiderHelper {
    public function __construct(
        public string $url                   = "",
        public int $status_code              = 0,
        public string $unique_images         = "{}",
        public string $unique_internal_links = "{}",
        public string $unique_external_links = "{}",
        public float $page_load_time         = 0.0,
        public int $word_count               = 0,
        public int $title_length             = 0
    ) {
        $response = $this->executeCurl($this->url);

        // capture status code before checking if there's any html in the response
        $this->status_code = $response["status_code"];

        if (empty($response["html"])) { // need html content to use DOMDocument, no point in proceeding
            return;
        }

        $dom = $this->loadDOM($response["html"]);

        // using $dom, acquire all the necessary content to fulfill requirements
        $this->unique_images         = json_encode($this->getImages($dom));
        $links = $this->getLinks($dom);
        $this->unique_internal_links = json_encode($links["internal"]);
        $this->unique_external_links = json_encode($links["external"]);
        $this->page_load_time        = $response["page_load_time"];
        $this->word_count            = $this->getWordCount($dom);
        $this->title_length          = $this->getTitleLength($dom);
    }

    /**
     * Executes a cURL request on the provided URL that we're scraping.
     *
     * @return array<string, int, float>
     */
    private function executeCurl() {
        $options = array(
            CURLOPT_URL            => $this->url,
            CURLOPT_CUSTOMREQUEST  => "GET",
            CURLOPT_POST           => false,
            CURLOPT_USERAGENT      => "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.70 Safari/537.36",
            CURLOPT_FOLLOWLOCATION => true, // follow redirects
            CURLOPT_ENCODING       => "", // handle encodings
            CURLOPT_HEADER         => false,
            CURLOPT_RETURNTRANSFER => true // return web page
        );
        $response = array(); 

        $ch = curl_init();
        curl_setopt_array($ch, $options);
        $response["html"] = curl_exec($ch);

        // use cURL info to get status code and page load time
        $info = curl_getinfo($ch);
        $response["status_code"]    = $info["http_code"];
        $response["page_load_time"] = $info["total_time"];
        curl_close($ch);

        return $response;
    }

    /**
     * Given html content in string form, create a DOMDocument for parsing.
     * 
     * @param string $html
     * @return \DOMDocument 
     */
    private function loadDOM($html) {
        if ($html == "") { // need html content to use DOMDocument, no point in proceeding
            return false;
        }
        else {
            $dom = new \DOMDocument("1.0", "utf-8");
            @$dom->loadHTML($html);
            $dom->preserveWhiteSpace = false;
    
            return $dom;
        }
    }

    /**
     * Calculate the length of the webpage title.
     * 
     * @param \DOMDocument $dom
     * @return int
     */
    private function getTitleLength($dom) {
        $titleNode = $dom->getElementsByTagName("title");
        if ($titleNode->length == 0) { // no title, so length is zero
            return 0;
        }

        // strip newline character, they count as 2 for the length and shouldn't be placed in title tags
        $title = str_replace("\n", "", $titleNode->item(0)->nodeValue);

        return strlen($title);
    }
    
    /**
     * Calculate the number of words on the page. For the purposes of accuracy, we will use the 
     * following criteria:
     *      1. We will only consider content between the body tags for the word count.
     *      2. All script and style tags will be removed, as well as inline comments.
     *      3. Punctuation and &nbsp; will be stripped, tags will be replaced with spaces.
     *      4. Numbers will not count, and most punctuation will be replaced by whitespace.
     * 
     * @param \DOMDocument $dom
     * @return int
     */
    private function getWordCount($dom) {
        $body_html = "";
        $body = $dom->getElementsByTagName("body");
        if ($body && $body->length > 0) { // we actually have body content
            $body_html = strtolower($dom->saveHTML($body->item(0)));
        }
        else { // can't proceed without body content
            return 0;
        }

        $remove_tags = array('@<script[^>]*?>.*?</script>@si', // strip out script tags
                             '@<style[^>]*?>.*?</style>@si',   // strip out style tags
                             '@<![\s\S]*?--[ \t\n\r]*>@'       // strip out comments
        );
        $temp_content = preg_replace($remove_tags, "", $body_html);
        $temp_content = trim(strip_tags(str_replace('<', ' <', $temp_content))); // remove tags
        
        // remove select punctuation and symbols from our content
        $ignore_chars = array(".", ",", ":", ";", "?", "!", "/", "|", "\"", "\xC2\xA0"); // last entry is &nbsp;
        $temp_content = str_replace($ignore_chars, " ", html_entity_decode($temp_content)); // replace select characters with space
        $temp_content = str_replace("-", "", $temp_content); // replace hyphens with no space, compound words count as 1 word

        return count(str_word_count($temp_content, 1));
    }

    /**
     * Calculate the number of unique internal and external links on the webpage.
     * 
     * @param \DOMDocument $dom
     * @return array<array<string>, array<string>>
     */
    private function getLinks($dom) {
        $links = array(
            "internal" => array(),
            "external" => array()
        );

        // iterate through all the anchor tags, determine internal/external by leading slash
        foreach ($dom->getElementsByTagName("a") as $tag) {
            $href = $tag->getAttribute("href");
            if (strlen($href) >= 1) {
                if (preg_match('/^[\/#]|^((https:\/\/)?(www.)?agencyanalytics.com)/', $href)) {
                    $links["internal"][] = in_array($href[0], array("/", "#")) ? ("https://www.agencyanalytics.com" . $href) : $href;
                }
                else {
                    $links["external"][] = $href;
                }
            }
        }
        
        // remove duplicates
        $links["internal"] = array_unique($links["internal"]);
        $links["external"] = array_unique($links["external"]);
        
        return $links;
    }

    /**
     * Calculate the number of unique images on the webpage (WARNING: does not capture lazyloaded images).
     * 
     * @param \DOMDocument $dom
     * @return array<string>
     */
    private function getImages($dom) {
        $images = array();

        // iterate through all the image tags, use src if present, data-src if not
        foreach ($dom->getElementsByTagName("img") as $tag) {
            $src = $tag->getAttribute("src");
            if ($src == "") {
                $src = $tag->getAttribute("data-src");
            }
            $images[] = $src;
        }

        // return images with duplicates removed
        return array_unique($images);
    }
}