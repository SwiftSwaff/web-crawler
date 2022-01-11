<?php
namespace Tests\Unit;
use App\Models\Spider;
use App\Helpers\SpiderHelper;
use PHPUnit\Framework\TestCase;

class TitleLengthTest extends TestCase {
    public function testBasic() {
        $spiderHelper = new SpiderHelper("https://crawler-test.com/titles/page_title_length/10");
        $this->assertIsInt($spiderHelper->title_length);
        $this->assertEquals(10, $spiderHelper->title_length);
    }

    public function testWhitespace() {
        $spiderHelper = new SpiderHelper("https://crawler-test.com/titles/title_with_whitespace");
        $this->assertIsInt($spiderHelper->title_length);
        $this->assertEquals(48, $spiderHelper->title_length);

        $spiderHelper = new SpiderHelper("https://crawler-test.com/titles/double_triple_quadruple_spaces");
        $this->assertIsInt($spiderHelper->title_length);
        $this->assertEquals(36, $spiderHelper->title_length);
    }
}