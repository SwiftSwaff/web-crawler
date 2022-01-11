<?php
namespace Tests\Unit;
use App\Models\Spider;
use App\Helpers\SpiderHelper;
use PHPUnit\Framework\TestCase;

class LinksTest extends TestCase {
    public function testInternalLinks() {
        $spiderHelper = new SpiderHelper("https://www.agencyanalytics.com/blog/new-integration-to-klaviyo");
        $this->assertEquals(62, count(json_decode($spiderHelper->unique_internal_links, true)));
    }
}
