<?php
namespace App\Agents;

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

        $client = new \GuzzleHttp\Client();
        $r = $client->get($config->get('url'));
        $body = $r->getBody()->getContents();

        if($config->get('type') == 'html') {
            $crawler = new Crawler($body);
            foreach ($config->get('extract') as $elements) {

                foreach ($crawler->filter($elements->path) as $nodes) {

                }
                exit;
//                $a = $crawler->filter($elements->);
            }
        }


//        $hrefs = [];
//        $contents = [];
//        foreach($a as $node) {
//            $hrefs[] = $node->getAttribute('href');
//            $contents[] = $node->textContent;
//        }


//        $client = new Client();
//        $crawler = $client->request('GET', $d->get('url'));
//
//        $crawler->filter();

        exit;

    }

    public function extractJson()
    {
        
    }

    public function extractXml()
    {
        
    }

    public function a()
    {
        
    }
}