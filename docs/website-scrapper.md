The Website Agent scrapes a website, XML document, or JSON feed and creates Events based on the results.

Example:
```json
{
  "expected_update_period_in_days": 2,
  "url": "http://www.reddit.com/r/all/comments/gilded.json",
  "type": "json",
  "mode": "on_change",
  "extract": {
    "body": { "path": "$.data.children[*].data.body" },
    "title": { "path": "$.data.children[*].data.link_title" }
  }
}
```

Specify a `url` and select a `mode` for when to create Events based on the scraped data, either `all`, `on_change`, or `merge` (if fetching based on an Event, see below).
The `url` option can be a single url, or an array of urls (for example, for multiple pages with the exact same structure but different content to scrape).
The WebsiteAgent can also scrape based on incoming events.
* Set the `url_from_event` option to a [Liquid](https://github.com/Muninn/Muninn/wiki/Formatting-Events-using-Liquid) template to generate the url to access based on the Event.  (To fetch the url in the Event's `url` key, for example, set `url_from_event` to `{{ url }}`.)
* Alternatively, set `data_from_event` to a [Liquid](https://github.com/Muninn/Muninn/wiki/Formatting-Events-using-Liquid) template to use data directly without fetching any URL.  (For example, set it to `{{ html }}` to use HTML contained in the `html` key of the incoming Event.)
* If you specify `merge` for the `mode` option, Muninn will retain the old payload and update it with new values.

# Supported Document Types
The `type` value can be `xml`, `html`, `json`, or `text`.
To tell the Agent how to parse the content, specify `extract` as a hash with keys naming the extractions and values of hashes.
Note that for all of the formats, whatever you extract MUST have the same number of matches for each extractor except when it has `repeat` set to true.  E.g., if you're extracting rows, all extractors must match all rows.  For generating CSS selectors, something like [SelectorGadget](http://selectorgadget.com) may be helpful.
For extractors with `hidden` set to true, they will be excluded from the payloads of events created by the Agent, but can be used and interpolated in the `template` option explained below.
For extractors with `repeat` set to true, their first matches will be included in all extracts.  This is useful such as when you want to include the title of a page in all events created from the page.

# Scraping HTML and XML
When parsing HTML or XML, these sub-hashes specify how each extraction should be done.  The Agent first selects a node set from the document for each extraction key by evaluating either a CSS selector in `css` or an XPath expression in `xpath`.  It then evaluates an XPath expression in `value` (default: `.`) on each node in the node set, converting the result into a string.  Here's an example:
```json
  "extract": {
    "url": { "css": "#comic img", "value": "@src" },
    "title": { "css": "#comic img", "value": "@title" },
    "body_text": { "css": "div.main", "value": "string(.)" },
    "page_title": { "css": "title", "value": "string(.)", "repeat": true }
  }
```
or
```json
  "extract": {
    "url": { "xpath": "//*[@class="blog-item"]/a/@href", "value": "."
    "title": { "xpath": "//*[@class="blog-item"]/a", "value": "normalize-space(.)" },
    "description": { "xpath": "//*[@class="blog-item"]/div[0]", "value": "string(.)" }
  }
```
"@_attr_" is the XPath expression to extract the value of an attribute named _attr_ from a node (such as "@href" from a hyperlink), and `string(.)` gives a string with all the enclosed text nodes concatenated without entity escaping (such as `&amp;`). To extract the innerHTML, use `./node()`; and to extract the outer HTML, use `.`.
You can also use [XPath functions](https://www.w3.org/TR/xpath/#section-String-Functions) like `normalize-space` to strip and squeeze whitespace, `substring-after` to extract part of a text, and `translate` to remove commas from formatted numbers, etc.  Instead of passing `string(.)` to these functions, you can just pass `.` like `normalize-space(.)` and `translate(., ',', '')`.
Beware that when parsing an XML document (i.e. `type` is `xml`) using `xpath` expressions, all namespaces are stripped from the document unless the top-level option `use_namespaces` is set to `true`.
For extraction with `array` set to true, all matches will be extracted into an array. This is useful when extracting list elements or multiple parts of a website that can only be matched with the same selector.

# Scraping JSON
When parsing JSON, these sub-hashes specify [JSONPaths](http://goessner.net/articles/JsonPath/) to the values that you care about.
Sample incoming event:
```json
  { "results": {
      "data": [
        {
          "title": "Lorem ipsum 1",
          "description": "Aliquam pharetra leo ipsum."
          "price": 8.95
        },
        {
          "title": "Lorem ipsum 2",
          "description": "Suspendisse a pulvinar lacus."
          "price": 12.99
        },
        {
          "title": "Lorem ipsum 3",
          "description": "Praesent ac arcu tellus."
          "price": 8.99
        }
      ]
    }
  }
  ```
Sample rule:
```json
  "extract": {
    "title": { "path": "results.data[*].title" },
    "description": { "path": "results.data[*].description" }
  }
  ```
In this example the `*` wildcard character makes the parser to iterate through all items of the `data` array. Three events will be created as a result.
Sample outgoing events:
```json
  [
    {
      "title": "Lorem ipsum 1",
      "description": "Aliquam pharetra leo ipsum."
    },
    {
      "title": "Lorem ipsum 2",
      "description": "Suspendisse a pulvinar lacus."
    },
    {
      "title": "Lorem ipsum 3",
      "description": "Praesent ac arcu tellus."
    }
  ]
```
The `extract` option can be skipped for the JSON type, causing the full JSON response to be returned.

# Scraping Text
When parsing text, each sub-hash should contain a `regexp` and `index`.  Output text is matched against the regular expression repeatedly from the beginning through to the end, collecting a captured group specified by `index` in each match.  Each index should be either an integer or a string name which corresponds to <code>(?&lt;<em>name</em>&gt;...)</code>.  For example, to parse lines of <code><em>word</em>: <em>definition</em></code>, the following should work:
```json
  "extract": {
    "word": { "regexp": "^(.+?): (.+)$", "index": 1 },
    "definition": { "regexp": "^(.+?): (.+)$", "index": 2 }
  }
```
Or if you prefer names to numbers for index:
```json
  "extract": {
    "word": { "regexp": "^(?<word>.+?): (?<definition>.+)$", "index": "word" },
    "definition": { "regexp": "^(?<word>.+?): (?<definition>.+)$", "index": "definition" }
  }
```
To extract the whole content as one event:
```json
  "extract": {
    "content": { "regexp": "\\A(?m:.)*\\z", "index": 0 }
  }
```
Beware that `.` does not match the newline character (LF) unless the `m` flag is in effect, and `^`/`$` basically match every line beginning/end.  See [this document](http://ruby-doc.org/core-#{RUBY_VERSION}/doc/regexp_rdoc.html) to learn the regular expression variant used in this service.

# General Options
Can be configured to use HTTP basic auth by including the `basic_auth` parameter with `"username:password"`, or `["username", "password"]`.
Set `expected_update_period_in_days` to the maximum amount of time that you'd expect to pass between Events being created by this Agent.  This is only used to set the "working" status.
Set `uniqueness_look_back` to limit the number of events checked for uniqueness (typically for performance).  This defaults to the larger of #{UNIQUENESS_LOOK_BACK} or #{UNIQUENESS_FACTOR}x the number of detected received results.
Set `force_encoding` to an encoding name (such as `UTF-8` and `ISO-8859-1`) if the website is known to respond with a missing, invalid, or wrong charset in the Content-Type header.  Below are the steps used by Muninn to detect the encoding of fetched content:
1. If `force_encoding` is given, that value is used.
2. If the Content-Type header contains a charset parameter, that value is used.
3. When `type` is `html` or `xml`, Muninn checks for the presence of a BOM, XML declaration with attribute "encoding", or an HTML meta tag with charset information, and uses that if found.
4. Muninn falls back to UTF-8 (not ISO-8859-1).
Set `user_agent` to a custom User-Agent name if the website does not like the default value (`#{default_user_agent}`).
The `headers` field is optional.  When present, it should be a hash of headers to send with the request.
Set `disable_ssl_verification` to `true` to disable ssl verification.
Set `unzip` to `gzip` to inflate the resource using gzip.
Set `http_success_codes` to an array of status codes (e.g., `[404, 422]`) to treat HTTP response codes beyond 200 as successes.
If a `template` option is given, its value must be a hash, whose key-value pairs are interpolated after extraction for each iteration and merged with the payload.  In the template, keys of extracted data can be interpolated, and some additional variables are also available as explained in the next section.  For example:
```json
  "template": {
    "url": "{{ url | to_uri: _response_.url }}",
    "description": "{{ body_text }}",
    "last_modified": "{{ _response_.headers.Last-Modified | date: '%FT%T' }}"
  }
```
In the `on_change` mode, change is detected based on the resulted event payload after applying this option.  If you want to add some keys to each event but ignore any change in them, set `mode` to `all` and put a DeDuplicationAgent downstream.

# Liquid Templating
In [Liquid](https://github.com/Muninn/Muninn/wiki/Formatting-Events-using-Liquid) templating, the following variables are available:

* `_url_`: The URL specified to fetch the content from.  When parsing `data_from_event`, this is not set.
* `_response_`: A response object with the following keys:
  * `status`: HTTP status as integer. (Almost always 200)  When parsing `data_from_event`, this is set to the value of the `status` key in the incoming Event, if it is a number or a string convertible to an integer.
  * `headers`: Response headers; for example, `{{ _response_.headers.Content-Type }}` expands to the value of the Content-Type header.  Keys are insensitive to cases and -/_.  When parsing `data_from_event`, this is constructed from the value of the `headers` key in the incoming Event, if it is a hash.
  * `url`: The final URL of the fetched page, following redirects.  When parsing `data_from_event`, this is set to the value of the `url` key in the incoming Event.  Using this in the `template` option, you can resolve relative URLs extracted from a document like `{{ link | to_uri: _response_.url }}` and `{{ content | rebase_hrefs: _response_.url }}`.
# Ordering Events
#{description_events_order}