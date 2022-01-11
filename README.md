## How to Use

Heroku Deployment: https://web-crawler-01102022.herokuapp.com/

There are two crawl methods available. 

"Use Custom" will take the base url "https://www.agencyanalytics.com/" with an optional subdirectory in the text input field, and perform a crawl of an additional 3-5 pages alongside the start point.

"Use Random" will crawl 4-6 pages found from the sitemap.xml file. The text input field does nothing to influence this operation.


## Notes

- Unique totals are calculated across all pages crawled.
- Internal links were determined from anchor tags that either started with some form of the scheme/domains, or started with a leading slash or hash.
- External links by process of elimination were links that did not fall under the internal link criteria.
- Page load times were taken from cURL total time info parameter.
- Word count was calculated by ignoring numbers and most symbols/punctuation, and only considered text content found in the body tag.
- Title length counts whitespace, but not newline characters.