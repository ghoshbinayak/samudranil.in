<?php
class Tumblr{
    private $userid = "ghoshbinayak.tumblr.com";
    private $apikey = "upraHHL2RL1JwKyg9LXX1TGyeJ8d0wZcFOus3xBf7x47pX1xyw";
    public function getPosts($offset, $maxresults){
        $feedURL = "http://api.tumblr.com/v2/blog/"
                    .$this->userid
                    ."/posts/text?api_key="
                    .$this->apikey
                    ."&limit="
                    .$maxresults
                    ."&offset="
                    .$offset;
        $response = json_decode(file_get_contents($feedURL));
        $status = $response->response->blog->description;
        $total_posts = $response->response->total_posts;
        $Posts = array(
                'status' => $status,
                'total_posts' => $total_posts,
                );
        foreach($response->response->posts as $entry){
            $posttitle = $entry->title;
            $postid = $entry->id;
            $timestamp = $entry->timestamp;
            $body = preg_replace("|<div(.+)>|", "", $entry->body);
            $body = preg_replace("|</div>|", "", $body);
            $Posts[] = array(
                        'title' => $posttitle,
                        'id' => $postid,
                        'time' => $timestamp,
                        'body' => $body);
            };
        return $Posts;
    }

    //get short posts
    public function getPostsShort($offset, $maxresult){
        $response = array_slice($this->getPosts($offset, $maxresult), 2);
        foreach($response as &$entry){
            $shortpost = preg_split("|<!--\smore\s-->|", $entry['body'], 2);
            $entry['body'] = $shortpost['0'];
        }
        return $response;
    }

    //get single post
    public function getSinglePost($postid){
        $feedURL = "http://api.tumblr.com/v2/blog/"
            .$this->userid
            ."/posts/text?api_key="
            .$this->apikey
            ."&id="
            .$postid;
        $response = json_decode(file_get_contents($feedURL));
        $post = $response->response->posts[0];
        $posttitle = $post->title;
        $body = $post->body;
        $timestamp = $post->timestamp;
        return array(
                    'title' => $posttitle,
                    'body' => $body,
                    'time' => $timestamp);
    }

}

$Tumblr = new Tumblr();
?>
