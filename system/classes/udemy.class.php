<?php

class udemy
{
    public $enable_proxies = false;

    function media_info($url)
    {
        $web_page = url_get_contents("https://wrapapi.com/use/txxx/udemy/free-course/0.0.2?url=$url&wrapAPIKey=7YQu0z1Qy6xPA5Dg6cReTQprt96iGj3g");
        $data = html_entity_decode(substr($web_page, 1, -1));
        $data = json_decode($data, true);
        if (isset($data["course"]) != "") {
            $video["source"] = "udemy";
            $video["title"] = $data["course"]["title"];
            $video["thumbnail"] = $data["asset"]["thumbnail_sprite"]["img_url"];
            $i = 0;
            foreach ($data["asset"]["stream_urls"]["Video"] as $item) {
                if ($item["label"] != "Auto") {
                    $video["links"][$i]["url"] = $item["file"];
                    $video["links"][$i]["type"] = "mp4";
                    $video["links"][$i]["size"] = get_file_size($video["links"][0]["url"]);
                    $video["links"][$i]["quality"] = $item["label"] . "p";
                    $video["links"][$i]["mute"] = "no";
                    $i++;
                }
            }
            return $video;
        } else {
            return false;
        }
    }
}