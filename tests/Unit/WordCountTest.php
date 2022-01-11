<?php
namespace Tests\Unit;
use App\Models\Spider;
use App\Helpers\SpiderHelper;
use PHPUnit\Framework\TestCase;

class WordCountTest extends TestCase {
    public function test100Words() {
        $spiderHelper = new SpiderHelper("https://crawler-test.com/content/word_count_100_words");
        $this->assertIsInt($spiderHelper->word_count);
        $this->assertEquals(100, $spiderHelper->word_count);
    }

    public function testSymbols() {
        $spiderHelper = new SpiderHelper("https://crawler-test.com/content/word_count_symbols");
        $this->assertIsInt($spiderHelper->word_count);
        $this->assertEquals(0, $spiderHelper->word_count);
    }
}
