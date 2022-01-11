<?php
use App\Http\Controllers\CrawlController;
use Illuminate\Support\Facades\Route;

Route::post("", [CrawlController::class, "crawl"]);
Route::get("", [CrawlController::class, "index"]);