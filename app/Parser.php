<?php

namespace App;

use Symfony\Component\DomCrawler\Crawler;
use Illuminate\Support\Facades\DB;
use DateTime;


class Parser
{
    public function getContent($link)
    {
        // Get html remote text.
        $html = file_get_contents($link);

        $crawler = new Crawler(null, $link);
        $crawler->addHtmlContent($html, 'UTF-8');
        $content = $crawler->filterXPath('//*[contains(@class, "post__body post__body_full")]')->html();
        $title = $crawler->filterXPath('//*[contains(@class, "post__title-text")]')->html();
        $created_at = $crawler->filterXPath('//*[contains(@class, "post__time")]')->html();
        $tags = [];
        $tagList =  $crawler->filterXPath('//*[contains(@class, "inline-list inline-list_fav-tags js-post-tags")]')->children();
        foreach ($crawler as $domElement) {
            array_push($tags, $domElement->ownerDocument->saveHTML($domElement));
        }        
        $articleId = 0;
        preg_match("/([0-9]){2,}/", $link, $articleId);
        $data = 
        [
            'text' => $content,
            'tags' => $tagList,
            'title' =>$title,
            'articleId' => $articleId[0],
            'created_at' => self::parseDate($created_at)
        ];
        
        return $data;
    }
    public function getMainPageContent($link, $idList)
    {
        $html = file_get_contents($link);

        $crawler = new Crawler(null, $link);
        $crawler->addHtmlContent($html, 'UTF-8');

        $links = [];
        foreach($crawler->filterXPath('//a[contains(@class, "post__title_link")]') as $l)
        {
            preg_match("/([0-9]){2,}/",$l->getAttribute('href'), $match);
            if(!in_array($match[0], $idList))
            { 
                array_push($links, $l->getAttribute('href'));
            }            
        }
        return $links;

    }
    public function startParse()
    {
        $mainLink = "https://habr.com/all/";
        $idList = self::getIdList();
        $articlesArray = [];
        $links_to_parse = [];
        $isExist = true;
        $pageNum = 1;
        do
        {
            if($pageNum == 1)
            {
                $result = self::getMainPageContent($mainLink, $idList);
                $links_to_parse = array_merge($links_to_parse, $result);
            }
            else
            {
                $nextLink = $mainLink."page".$pageNum;
                if($fp = curl_init($nextLink)) 
                {
                    $result = self::getMainPageContent($nextLink, $idList);  
                    $links_to_parse = array_merge($links_to_parse, $result);
                }       
            }
            $pageNum++;
        }
        while($pageNum < 100);
        if(count($links_to_parse) > 0)
        {
            for($i=0; $i < count($links_to_parse)-1; $i++)
            {
                $article = self::getContent($links_to_parse[$i]);
                self::writeToDB($article);
            }
        }
    }
    
    public function writeToDB($article)
    {
        DB::insert('insert into posts (text, articleId, created_at) values (?, ?, ?)',
         [$article['text'], $article['articleId'], $article['created_at']]);        
    }

    public function getLastId()
    {
        $lastId = DB::table('posts')->max('articleId');
        return $lastId;
    }

    public function getIdList()
    {
        $result = DB::table('posts')->pluck('articleId')->toArray();
        return $result;
    }

    public function parseDate($value)
    {
        $alphaDate = preg_split("/[ :]+/", $value);
        $date = new DateTime();

        if(count($alphaDate) == 4)
        {
            $currentDate = getdate();
            if($alphaDate[0] == 'сегодня'){
                $date->setDate($currentDate['year'], $currentDate['mon'], $currentDate['mday']);
                
            }
            else if($alphaDate[0] == 'вчера'){
                $date->setDate($currentDate['year'], $currentDate['mon'], $currentDate['mday']-1);
            }
            $date->setTime($alphaDate[2], $alphaDate[3]);
        }
        else{
            $date->setDate($alphaDate[2], self::parseMonth($alphaDate[1]), $alphaDate[0]);
            $date->setTime($alphaDate[4], $alphaDate[5]);
        }
        return $date;
    }

    public function parseMonth($month)
    {
        $monthArray =
        [
            'января' => 1,
            'февраля' => 2,
            'марта' => 3,
            'апреля' => 4,
            'мая' => 5,
            'июня' => 6,
            'июля' => 7,
            'августа' => 8,
            'сентября' => 9,
            'октября' => 10,
            'ноября' => 11,
            'декабря' => 12
        ];
        return $monthArray[$month];
    }
}
