<?php
namespace App\Http\Controllers;
use App\Services\CrawlService;
use Illuminate\Http\Request;

/**
 * CrawlController handles the app's routing, and delegates business logic to App\Services\CrawlService 
 * in order to maintain a skinny controller.
 *
 */
class CrawlController extends Controller {
    /**
     * Post request that delegates crawling. Takes summary result data and passes it to home page for display.
     * 
     * @param Request $request
     * @return view
     */
    public function crawl(Request $request) {
        $crawlService = new CrawlService();
        $summary = ($request->action == "Use Custom") ? $crawlService->customCrawl($request->url) : $crawlService->randomCrawl();
        return view("index", ["summary" => $summary]);
    }

    /**
     * Home page with only the options to crawl on it.
     * 
     * @return view
     */
    public function index() {
        return view("index", ["summary" => null]);
    }
}
