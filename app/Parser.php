<?php

namespace App;

use Symfony\Component\DomCrawler\Crawler;

class Parser
{
    public function getMainPageContent($link)
    {
        $html = file_get_contents($link);

        $crawler = new Crawler(null, $link);
        $crawler->addHtmlContent($html, 'UTF-8');

        $links = [];
        foreach($crawler->filterXPath('//a[contains(@class, "post__title_link")]') as $l)
        {
            array_push($links, $l->getAttribute('href'));
        }
        return $links;

    }
    public function getContent($parser, $link)
    {
        // Get html remote text.
        $html = file_get_contents($link);

        $crawler = new Crawler(null, $link);
        $crawler->addHtmlContent($html, 'UTF-8');

        
    }
}
