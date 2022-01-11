@extends("layout")

@section("main")
<div id="form_container">
    <form id="crawl_form" method="POST" action="/">
        @csrf
        <label for="url">https://www.agencyanalytics.com/</label>
        <input type="text" id="url" name="url" placeholder="Enter Subdirectory Here"/>
        <div id="submit_container">
            <input type="submit" name="action" value="Use Custom"/>
            <input type="submit" name="action" value="Use Random"/>
        </div>
    </form>
</div>
@if (!is_null($summary))
<div id="summary_container">
    <ul>
        <li>Pages Crawled: {{ $summary->page_count }}</li>
        <li>Unique Images: {{ $summary->unique_image_count }}</li>
        <li>Unique Internal Links: {{ $summary->unique_internal_link_count }}</li>
        <li>Unique External Links: {{ $summary->unique_external_link_count }}</li>
        <li>Average Page Load Time: {{ $summary->average_page_load_time }}</li>
        <li>Average Word Count: {{ $summary->average_word_count }}</li>
        <li>Average Title Length: {{ $summary->average_title_length }}</li>
    </ul>
    <table>
        <thead>
            <tr><th>URL</th><th>HTTP Status Code</th></tr>
        </thead>
        <tbody>
            @foreach ($summary->spiders as $spider)
            <tr><td>{{ $spider->url }}</td><td>{{ $spider->status_code }}</td></tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif
@endsection