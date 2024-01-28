<?php

class odnoklassniki
{
    public $enable_proxies = false;

    function media_info($url)
    {
        $video_id = str_replace("video/", "", substr(parse_url($url, PHP_URL_PATH), 1));
        $data = $this->get_data($video_id);
        if (isset($data) != "") {
            $data = json_decode($data, true);
            $video["source"] = "ok.ru";
            $video["title"] = $data["movie"]["title"];
            $video["thumbnail"] = $data["movie"]["poster"];
            $video['time'] = gmdate(($data["movie"]["duration"] > 3600 ? "H:i:s" : "i:s"), $data["movie"]["duration"]);
            $i = 0;
            foreach ($data["videos"] as $item) {
                $video["links"][$i]["url"] = $item["url"];
                $video["links"][$i]["type"] = "mp4";
                $video["links"][$i]["size"] = get_file_size($video["links"][0]["url"], $this->enable_proxies);
                $video["links"][$i]["quality"] = $item["name"];
                $video["links"][$i]["mute"] = "no";
                $i++;
            }
            return $video;
        } else {
            return false;
        }
    }

    function get_data($video_id)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://ok.ru/dk?cmd=videoPlayerMetadata&mid=$video_id",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST"
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            return false;
        } else {
            return $response;
        }
    }
}