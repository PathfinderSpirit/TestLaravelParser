<?php

namespace App\Libraries;

use Symfony\Component\DomCrawler\Crawler;
use Illuminate\Support\Facades\DB;
use App\Post;
use App\Tag;
use App\Libraries\DateParser;


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
        $tagList = $crawler->filterXPath('//*[contains(@class, "inline-list__item-link post__tag  ")]')->each(function (Crawler $node, $i) {
            return $node->text();
        });     

        $articleId = 0;
        preg_match("/([0-9]){2,}/", $link, $articleId);
        $data = 
        [
            'text' => $content,
            'tags' => $tagList,
            'title' =>$title,
            'articleId' => $articleId[0],
            'created_at' => DateParser::parseDate($created_at)
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
        $idList = Post::all()->pluck('articleId')->toArray();
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
    //writes grabbed post to DataBase
    public function writeToDB($article)
    {
        $tagList = Tag::all()->pluck('id')->toArray(); //get all tags for double checking 
        $post = Post::create(['title' => $article['title'],
                            'text' => $article['text'],
                            'articleId' => $article['articleId'],
                            'created_at' => $article['created_at']]); 
        foreach($article['tags'] as $tag)
        {
            $currentTag = Tag::where('name', $tag)->first();
            if(!$currentTag){ 
                $currentTag = Tag::create(['name' => $tag]);     
            }
            DB::table("post_to_tags")->insert(['tag_ref' => $currentTag->id, 'post_ref' => $post->id]);
           //DB::insert('insert into post_to_tags (tag_ref, post_ref) values (?,?)', [$currentTag->id, $post->id]);
        }        
    }
}

    

   

    
