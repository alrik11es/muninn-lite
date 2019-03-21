<?php
namespace App\Agents;

use App\EventHelper;
use App\Models\Event;
use Carbon\Carbon;
use function GuzzleHttp\Promise\queue;
use Symfony\Component\DomCrawler\Crawler;

class WebsiteScrapper implements Agent
{
    public $name = 'Website scrapper agent';
    public $description = 'The Website Agent scrapes a website, XML document, '.
                          'or JSON feed and creates Events based on the results.';

    public function process(\App\Models\Agent $agent)
    {
        $content = file_get_contents($agent->config_location);
        $content = json_decode($content);

        $config = \Alr\ObjectDotNotation\Data::load($content);

        $body = $this->getResponseBody($config->get('url'));

        $result = $this->extractXml($config, $body);

        // Save buffer
        // Compare buffer
        // Send events

        $eh = new EventHelper();
        foreach($result as $item) {
            $eh->generateEvent($agent, $item);
        }

        $agent->last_check = Carbon::now();
        $agent->save();
//        $this->info($agent->name .' processed '.count($result).' events');
    }

    public function extractJson()
    {
        
    }

    public function extractXml($config, $body)
    {
        $arr = [];
        if($config->get('type') == 'html') {
            $crawler = new Crawler($body);

            foreach ($config->get('extract') as $key => $elements) {
                foreach ($crawler->filter($elements->path) as $index => $nodes) {
//                    $html = $nodes->ownerDocument->saveHTML($nodes);
                    if (preg_match('/@/', $elements->value)){
                        $attr = str_replace('@', '', $elements->value);
                        $arr[$index][$key] = $nodes->getAttribute($attr);
                    }
                    if(preg_match('/string\(\.\)/', $elements->value)) {
                        $arr[$index][$key] = $nodes->textContent;
                    }
                }
            }
        }

        $arr_final = [];
        foreach($arr as $el) {
            $arr_final[] = (object) $el;
        }

        return $arr_final;
    }

    public function extractText()
    {
        
    }

    /**
     * @param \Alr\ObjectDotNotation\Data $config
     * @return string
     */
    public function getResponseBody($url): string
    {
        $client = new \GuzzleHttp\Client();
        $r = $client->get($url);
        $body = $r->getBody()->getContents();
        return $body;
    }
}