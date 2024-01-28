<?php

class puhutv
{
    public $enable_proxies = false;

    function media_info($url)
    {
        $web_page = url_get_contents($url, $this->enable_proxies);
        if (preg_match_all('@"assetId":(.*?),@si', $web_page, $match)) {
            $video_id = $match[1][0];
        }
        if (preg_match('@name="og:title" content="(.*?)"@si', $web_page, $title)) {
            $data["title"] = $title[1];
        }
        if (preg_match('@name="og:image" content="(.*?)"@si', $web_page, $thumbnail)) {
            $data["thumbnail"] = $thumbnail[1];
        }
        $api_url = "https://puhutv.com/api/assets/" . $video_id . "/videos";
        $api_page = url_get_contents($api_url, $this->enable_proxies);
        $api_json = json_decode($api_page, true)["data"]["videos"];
        $i = 0;
        foreach ($api_json as $current) {
            if ($current["video_format"] == "mp4") {
                $data["links"][$i]["url"] = $current["url"];
                $data["links"][$i]["type"] = "mp4";
                $data["links"][$i]["size"] = get_file_size($data["links"][$i]["url"], $this->enable_proxies);
                $data["links"][$i]["quality"] = $current["quality"] . "p";
                $data["links"][$i]["mute"] = "no";
                $i++;
            }
        }
        $data["source"] = "puhutv";
        return $data;
    }
}