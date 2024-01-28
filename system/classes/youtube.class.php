<?php
require_once __DIR__ . "/vendor/autoload.php";
use YouTube\YouTubeDownloader;

class youtube
{
    public $m4a_mp3 = true;
    public $hide_dash_videos = false;
    public $enable_proxies = false;

    function media_info($url)
    {
        if ($this->enable_proxies) {
            $yt = new YouTubeDownloader(get_proxy());
        } else {
            $yt = new YouTubeDownloader();
        }
        $data = $yt->getDownloadLinks($url);
        $links = $data["links"];
        $json = $data["json"];
        $video["title"] = $json["videoDetails"]["title"];
        $video["thumbnail"] = "https://i.ytimg.com/vi/" . $json["videoDetails"]["videoId"] . "/mqdefault.jpg";
        $video["duration"] = format_seconds($json["videoDetails"]["lengthSeconds"]);
        $video["source"] = "youtube";
        $video["links"] = array();
        $itags = array(5 => array('extension' => 'flv', 'video' => array('width' => 400, 'height' => 240,),), 6 => array('extension' => 'flv', 'video' => array('width' => 450, 'height' => 270,),), 13 => array('extension' => '3gp',), 17 => array('extension' => '3gp', 'video' => array('width' => 176, 'height' => 144,),), 18 => array('extension' => 'mp4', 'video' => array('width' => 640, 'height' => 360,),), 22 => array('extension' => 'mp4', 'video' => array('width' => 1280, 'height' => 720,),), 34 => array('extension' => 'flv', 'video' => array('width' => 640, 'height' => 360,),), 35 => array('extension' => 'flv', 'video' => array('width' => 854, 'height' => 480,),), 36 => array('extension' => '3gp', 'video' => array('width' => 320, 'height' => 240,),), 37 => array('extension' => 'mp4', 'video' => array('width' => 1920, 'height' => 1080,),), 38 => array('extension' => 'mp4', 'video' => array('width' => 4096, 'height' => 3072,),), 43 => array('extension' => 'webm', 'video' => array('width' => 640, 'height' => 360,),), 44 => array('extension' => 'webm', 'dash' => false, 'video' => array('width' => 854, 'height' => 480,),), 45 => array('extension' => 'webm', 'video' => array('width' => 1280, 'height' => 720,),), 46 => array('extension' => 'webm', 'video' => array('width' => 1920, 'height' => 1080,),), 59 => array('extension' => 'mp4', 'video' => array('width' => 854, 'height' => 480,),), 78 => array('extension' => 'mp4', 'video' => array('width' => 854, 'height' => 480,),), 82 => array('extension' => 'mp4', 'video' => array('3d' => true, 'width' => 640, 'height' => 360,),), 83 => array('extension' => 'mp4', 'video' => array('3d' => true, 'width' => 854, 'height' => 480,),), 84 => array('extension' => 'mp4', 'video' => array('3d' => true, 'width' => 1280, 'height' => 720,),), 85 => array('extension' => 'mp4', 'video' => array('3d' => true, 'width' => 1920, 'height' => 1080,),), 100 => array('extension' => 'webm', 'video' => array('3d' => true, 'width' => 640, 'height' => 360,),), 101 => array('extension' => 'webm', 'video' => array('3d' => true, 'width' => 854, 'height' => 480,),), 102 => array('extension' => 'webm', 'video' => array('3d' => true, 'width' => 1280, 'height' => 720,),), 133 => array('extension' => 'mp4', 'dash' => 'video', 'video' => array('width' => 426, 'height' => 240,),), 134 => array('extension' => 'mp4', 'dash' => 'video', 'video' => array('width' => 640, 'height' => 360,),), 135 => array('extension' => 'mp4', 'dash' => 'video', 'video' => array('width' => 854, 'height' => 480,),), 136 => array('extension' => 'mp4', 'dash' => 'video', 'video' => array('width' => 1280, 'height' => 720,),), 137 => array('extension' => 'mp4', 'dash' => 'video', 'video' => array('width' => 1920, 'height' => 1080,),), 138 => array('extension' => 'mp4', 'dash' => 'video', 'video' => array('width' => 4096, 'height' => 2304,),), 394 => array('extension' => 'mp4', 'dash' => 'video', 'video' => array('width' => 256, 'height' => 144,),), 395 => array('extension' => 'mp4', 'dash' => 'video', 'video' => array('width' => 426, 'height' => 240,),), 396 => array('extension' => 'mp4', 'dash' => 'video', 'video' => array('width' => 640, 'height' => 360,),), 397 => array('extension' => 'mp4', 'dash' => 'video', 'video' => array('width' => 854, 'height' => 480,),), 398 => array('extension' => 'mp4', 'dash' => 'video', 'video' => array('width' => 1280, 'height' => 720,),), 399 => array('extension' => 'mp4', 'dash' => 'video', 'video' => array('width' => 1920, 'height' => 1080,),), 139 => array('extension' => 'm4a', 'dash' => 'audio', 'audio' => array('bitrate' => 48000, 'frequency' => 22050,),), 140 => array('extension' => 'm4a', 'dash' => 'audio', 'audio' => array('bitrate' => 128000, 'frequency' => 44100,),), 141 => array('extension' => 'm4a', 'dash' => 'audio', 'audio' => array('bitrate' => 256000, 'frequency' => 44100,),), 160 => array('extension' => 'mp4', 'dash' => 'video', 'video' => array('width' => 256, 'height' => 144,),), 167 => array('extension' => 'webm', 'dash' => 'video', 'video' => array('width' => 640, 'height' => 360,),), 168 => array('extension' => 'webm', 'dash' => 'video', 'video' => array('width' => 854, 'height' => 480,),), 169 => array('extension' => 'webm', 'dash' => 'video', 'video' => array('width' => 1280, 'height' => 720,),), 170 => array('extension' => 'webm', 'dash' => 'video', 'video' => array('width' => 1920, 'height' => 1080,),), 171 => array('extension' => 'webm', 'dash' => 'audio', 'audio' => array('bitrate' => 128000, 'frequency' => 44100,),), 172 => array('extension' => 'webm', 'dash' => 'audio', 'audio' => array('bitrate' => 192000, 'frequency' => 44100,),), 218 => array('extension' => 'webm', 'dash' => 'video', 'video' => array('width' => 854, 'height' => 480,),), 219 => array('extension' => 'webm', 'dash' => 'video', 'video' => array('width' => 854, 'height' => 480,),), 242 => array('extension' => 'webm', 'dash' => 'video', 'video' => array('width' => 427, 'height' => 240,),), 243 => array('extension' => 'webm', 'dash' => 'video', 'video' => array('width' => 640, 'height' => 360,),), 244 => array('extension' => 'webm', 'dash' => 'video', 'video' => array('width' => 854, 'height' => 480,),), 245 => array('extension' => 'webm', 'dash' => 'video', 'video' => array('width' => 854, 'height' => 480,),), 246 => array('extension' => 'webm', 'dash' => 'video', 'video' => array('width' => 854, 'height' => 480,),), 247 => array('extension' => 'webm', 'dash' => 'video', 'video' => array('width' => 1280, 'height' => 720,),), 248 => array('extension' => 'webm', 'dash' => 'video', 'video' => array('width' => 1920, 'height' => 1080,),), 249 => array('extension' => 'webm', 'dash' => 'audio', 'audio' => array('bitrate' => 50000, 'frequency' => 48000,),), 250 => array('extension' => 'webm', 'dash' => 'audio', 'audio' => array('bitrate' => 65000, 'frequency' => 48000,),), 251 => array('extension' => 'webm', 'dash' => 'audio', 'audio' => array('bitrate' => 158000, 'frequency' => 48000,),), 264 => array('extension' => 'mp4', 'dash' => 'video', 'video' => array('width' => 2560, 'height' => 1440,),), 266 => array('extension' => 'mp4', 'dash' => 'video', 'video' => array('width' => 3840, 'height' => 2160,),), 271 => array('extension' => 'webm', 'dash' => 'video', 'video' => array('3d' => false, 'width' => 2560, 'height' => 1440,),), 272 => array('extension' => 'webm', 'dash' => 'video', 'video' => array('width' => 3840, 'height' => 2160,),), 278 => array('extension' => 'webm', 'dash' => 'video', 'video' => array('width' => 256, 'height' => 144,),), 298 => array('extension' => 'mp4', 'dash' => 'video', 'video' => array('width' => 1280, 'height' => 720,),), 299 => array('extension' => 'mp4', 'dash' => 'video', 'video' => array('width' => 1920, 'height' => 1080,),), 302 => array('extension' => 'webm', 'dash' => 'video', 'video' => array('width' => 1280, 'height' => 720,),), 303 => array('extension' => 'webm', 'dash' => 'video', 'video' => array('width' => 1920, 'height' => 1080,),), 308 => array('extension' => 'webm', 'dash' => 'video', 'video' => array('width' => 2560, 'height' => 1440,),), 313 => array('extension' => 'webm', 'dash' => 'video', 'video' => array('width' => 3840, 'height' => 2026,),), 400 => array('extension' => 'mp4', 'dash' => 'video', 'video' => array('width' => 2560, 'height' => 1440,),), 401 => array('extension' => 'mp4', 'dash' => 'video', 'video' => array('width' => 3840, 'height' => 2160,),),);
        foreach ($links as $link) {
            if (!empty($itags[($link["itag"] ?? "")])) {
                $is_audio = (isset($itags[$link["itag"]]["video"]) != "") ? false : true;
                $is_dash = isset($itags[$link["itag"]]["dash"]) != "";
                $type = ($is_audio && $this->m4a_mp3 && $itags[$link["itag"]]["extension"] == "m4a") ? "mp3" : $itags[$link["itag"]]["extension"];
                $file_size = get_file_size($link["url"]);
                $quality = (!$is_audio) ? $itags[$link["itag"]]["video"]["height"] . "p" : $this->format_bitrate($itags[$link["itag"]]["audio"]["bitrate"]);
                if ($is_dash && $this->hide_dash_videos) {
                    $is_hidden = true;
                } else {
                    array_push($video["links"], array(
                        "url" => $link["url"],
                        "type" => $type,
                        "itag" => $link["itag"],
                        "quality" => $quality,
                        "mute" => $is_dash,
                        "size" => $file_size
                    ));
                    if ($type == "mp3" && $itags[$link["itag"]]["extension"] == "m4a") {
                        array_push($video["links"], array(
                            "url" => $link["url"],
                            "type" => "m4a",
                            "itag" => $link["itag"],
                            "quality" => $quality,
                            "mute" => $is_dash,
                            "size" => $file_size
                        ));
                    }
                }
            }
        }
        usort($video["links"], 'sort_by_quality');
        return $video;
    }

    private function format_bitrate($bitrate)
    {
        if ($bitrate >= 1073741824) {
            $bitrate = number_format($bitrate / 1073741824, 2) . ' GB';
        } elseif ($bitrate >= 1048576) {
            $bitrate = number_format($bitrate / 1048576, 2) . ' MB';
        } elseif ($bitrate >= 1024) {
            $bitrate = number_format($bitrate / 1024, 2) . ' KB';
        } elseif ($bitrate > 1) {
            $bitrate = $bitrate . ' bytes';
        } elseif ($bitrate === 1) {
            $bitrate = $bitrate . ' byte';
        } else {
            $bitrate = '0 bytes';
        }
        $kb = (int)$bitrate;
        return $kb . ' kbps';
    }
}