<?php
namespace Tests\Unit;
use App\Models\Spider;
use App\Helpers\SpiderHelper;
use PHPUnit\Framework\TestCase;

class StatusCodeTest extends TestCase {
    public function testHttp200to226() {
        $status_codes = array(200,201,202,203,204,205,206,207,226);
        foreach ($status_codes as $code) {
            $spiderHelper = new SpiderHelper("https://crawler-test.com/status_codes/status_{$code}");
            $this->assertIsInt($spiderHelper->status_code);
            $this->assertEquals($code, $spiderHelper->status_code);
        }
    }

    public function testHttp400to410() {
        $status_codes = array(400,401,402,403,404,405,406,407,408,409,410);
        foreach ($status_codes as $code) {
            $spiderHelper = new SpiderHelper("https://crawler-test.com/status_codes/status_{$code}");
            $this->assertIsInt($spiderHelper->status_code);
            $this->assertEquals($code, $spiderHelper->status_code);
        }
    }

    public function testHttp411to420() {
        $status_codes = array(411,412,413,414,415,416,417,418,419,420);
        foreach ($status_codes as $code) {
            $spiderHelper = new SpiderHelper("https://crawler-test.com/status_codes/status_{$code}");
            $this->assertIsInt($spiderHelper->status_code);
            $this->assertEquals($code, $spiderHelper->status_code);
        }
    }

    public function testHttp421to449() {
        $status_codes = array(421,422,423,424,426,428,429,431,440,444,449);
        foreach ($status_codes as $code) {
            $spiderHelper = new SpiderHelper("https://crawler-test.com/status_codes/status_{$code}");
            $this->assertIsInt($spiderHelper->status_code);
            $this->assertEquals($code, $spiderHelper->status_code);
        }
    }

    public function testHttp450to499() {
        $status_codes = array(450,451,494,495,496,497,498,499);
        foreach ($status_codes as $code) {
            $spiderHelper = new SpiderHelper("https://crawler-test.com/status_codes/status_{$code}");
            $this->assertIsInt($spiderHelper->status_code);
            $this->assertEquals($code, $spiderHelper->status_code);
        }
    }

    public function testHttp500to599() {
        $status_codes = array(500,501,502,503,504,505,506,507,508,509,510,511,520,598,599);
        foreach ($status_codes as $code) {
            $spiderHelper = new SpiderHelper("https://crawler-test.com/status_codes/status_{$code}");
            $this->assertIsInt($spiderHelper->status_code);
            $this->assertEquals($code, $spiderHelper->status_code);
        }
    }
}
