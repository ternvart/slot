<?php

class tiktok
{
    public $enable_proxies = false;

    function media_info($url)
    {
        $url = unshorten($url);
        $web_page = url_get_contents($url);
        preg_match_all('/<script id="__NEXT_DATA__" type="application\/json" crossorigin="anonymous">(.*?)<\/script>/', $web_page, $match);
        if (isset($match[1][0]) != "") {
            $data = json_decode($match[1][0], true);
            $video["source"] = "tiktok";
            $video["title"] = $data["props"]["pageProps"]["shareMeta"]["title"];
            $thumbnail = get_string_between($web_page, '"thumbnailUrl":["', '"');
            if (!empty($data['props']['pageProps']['shareMeta']['image']['url'])) {
                $video["thumbnail"] = $data['props']['pageProps']['shareMeta']['image']['url'];
            } else if (!empty($thumbnail)) {
                $video["thumbnail"] = $thumbnail;
            } else {
                $video["thumbnail"] = "https://s16.tiktokcdn.com/musical/resource/wap/static/image/logo_144c91a.png?v=2";
            }
            $video_url = $data["props"]["pageProps"]["videoData"]["itemInfos"]["video"]["urls"][0];
            $video_data = url_get_contents($video_url);
            $matches = array();
            $pattern = '/vid:([a-zA-Z0-9]+)/';
            preg_match($pattern, $video_data, $matches);
            if (count($matches) > 1) {
                $video["links"][0]["url"] = "https://api2.musical.ly/aweme/v1/playwm/?video_id=" . $matches[1];
            } else {
                $video["links"][0]["url"] = $video_url;
            }
            $video["links"][0]["type"] = "mp4";
            $video["links"][0]["size"] = get_file_size($video["links"][0]["url"]);
            $video["links"][0]["quality"] = "hd";
            $video["links"][0]["mute"] = "no";
            if (count($matches) > 1) {
                $video["links"][1]["url"] = $video_url;
                $video["links"][1]["type"] = "mp4";
                $video["links"][1]["size"] = get_file_size($video["links"][1]["url"]);
                $video["links"][1]["quality"] = "watermarked";
                $video["links"][1]["mute"] = "no";
            }
            $audio_url = $data['props']['pageProps']['videoObjectPageProps']['videoProps']['audio']['mainEntityOfPage']['@id'];
            $audio_data = url_get_contents($audio_url);
            $start = preg_quote('<script id="__NEXT_DATA__" type="application/json" crossorigin="anonymous">', '/');
            $end = preg_quote('</script>', '/');
            preg_match("/$start(.*?)$end/", $audio_data, $audio_data);
            if (count($audio_data) > 1) {
                $audio_data = json_decode($audio_data[1], true);
                $video["links"][2]["url"] = $audio_data['props']['pageProps']['musicInfo']['music']['playUrl'];
                $video["links"][2]["type"] = "mp3";
                $video["links"][2]["size"] = get_file_size($video["links"][2]["url"]);
                $video["links"][2]["quality"] = "128 kbps";
                $video["links"][2]["mute"] = "no";
            }

            return $video;
        } else {
            preg_match_all('/\bdata\s*=\s*({.+?})\s*;/', $web_page, $match);
            preg_match_all('@window.__INIT_PROPS__ =(.*?)</script>@si', $web_page, $match2);
            if (isset($match[1][0]) != "") {
                $data = json_decode($match[1][0], true);
                $video["source"] = "tiktok";
                $video["title"] = $data["share_info"]["share_title"];
                $video["thumbnail"] = $data["video"]["cover"]["preview_url"];
                $video["links"][0]["url"] = "https:" . $data["video"]["play_addr"]["url_list"][0];
                $video["links"][0]["type"] = "mp4";
                $video["links"][0]["size"] = get_file_size($video["links"][0]["url"]);
                $video["links"][0]["quality"] = "hd";
                $video["links"][0]["mute"] = "no";
                return $video;
            } else if (isset($match2[0][0]) != "") {
                $data = json_decode($match2[0][0], true)["/v/:id"];
                $video["source"] = "tiktok";
                $video["title"] = $data["shareMeta"]["title"];
                $video["thumbnail"] = $data["videoData"]["itemInfos"]["covers"][0];
                $video["links"][0]["url"] = $data["videoData"]["itemInfos"]["video"]["urls"][0];
                $video["links"][0]["type"] = "mp4";
                $video["links"][0]["size"] = get_file_size($video["links"][0]["url"]);
                $video["links"][0]["quality"] = "hd";
                $video["links"][0]["mute"] = "no";
                return $video;
            } else {
                return false;
            }
        }
    }
}