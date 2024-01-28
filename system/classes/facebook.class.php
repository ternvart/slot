<?php

class facebook
{
    public $enable_proxies = false;
    public $url;

    function get_domain($url)
    {
        $pieces = parse_url($url);
        $domain = isset($pieces['host']) ? $pieces['host'] : '';
        if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)) {
            return $regs['domain'];
        } else {
            return false;
        }
    }

    function media_info($url)
    {
        $url = unshorten($this->remove_m($url));
        $curl_content = url_get_contents($url, $this->enable_proxies);
        $video["title"] = $this->get_title($curl_content);
        $video["source"] = "facebook";
        $video["thumbnail"] = $this->get_thumbnail($curl_content);
        $video["links"] = array();
        $sd_link = $this->sd_link($curl_content);
        if (!filter_var($sd_link, FILTER_VALIDATE_URL)) {
            $sd_link = get_string_between($curl_content, 'property="og:video" content="', '"');
            $sd_link = str_replace("&amp;", "&", $sd_link);
        }
        if (!empty($sd_link)) {
            array_push($video["links"], array(
                "url" => $sd_link,
                "type" => "mp4",
                "size" => get_file_size($sd_link, $this->enable_proxies),
                "quality" => "SD",
                "mute" => "no"
            ));
        }
        $hd_link = $this->hd_link($curl_content);
        if (!empty($hd_link)) {
            array_push($video["links"], array(
                "url" => $hd_link,
                "type" => "mp4",
                "size" => get_file_size($hd_link, $this->enable_proxies),
                "quality" => "HD",
                "mute" => "no"
            ));
        }
        if (empty($video["links"])) {
            preg_match_all('/FBQualityLabel="(\d{3})p"><BaseURL>(.*?)<\/BaseURL>/', $this->format_page($curl_content), $output);
            if (!empty($output[1]) && !empty($output[2])) {
                for ($i = 0; $i < count($output[1]); $i++) {
                    $decoded_url = str_replace("&amp;", "&", $output[2][$i]);
                    array_push($video["links"], array(
                        "url" => $decoded_url,
                        "type" => "mp4",
                        "size" => get_file_size($decoded_url, $this->enable_proxies),
                        "quality" => $output[1][$i] . "p",
                        "mute" => "no"
                    ));
                }
                usort($video["links"], "sort_by_quality");
            }
        }
        return $video;
    }

    function change_domain($url)
    {
        $domain = $this->get_domain($url);
        $parse_url = parse_url($url);
        switch ($domain) {
            case "facebook.com":
                return "https://m.facebook.com" . $parse_url["path"] . "?" . $parse_url["query"];
                break;
            case "m.facebook.com":
                return "https://www.facebook.com" . $parse_url["path"] . "?" . $parse_url["query"];
                break;
            default:
                return "https://www.facebook.com" . $parse_url["path"] . "?" . $parse_url["query"];
                break;
        }
    }

    function clean_str($str)
    {
        return html_entity_decode(strip_tags($str), ENT_QUOTES, 'UTF-8');
    }

    function format_page($html)
    {
        $html = str_replace("\\x3CBaseURL>", "<BaseURL>", $html);
        $html = str_replace("\\x3C/BaseURL>", "</BaseURL>", $html);
        $html = str_replace('\"', '"', $html);
        return $html;
    }

    function convert_url($url)
    {
        $url = preg_replace_callback('/\\\\u([0-9a-fA-F]{4})/', function ($match) {
            return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');
        }, $url);
        return str_replace("\/", "/", $url);
    }

    function remove_m($url)
    {
        $url = str_replace("m.facebook.com", "www.facebook.com", $url);
        return $url;
    }

    function mobil_link($curl_content)
    {
        $regex = '@&quot;https:(.*?)&quot;,&quot;@si';
        if (preg_match_all($regex, $curl_content, $match)) {
            return $match[1][0];
        }
    }

    function hd_link($curl_content)
    {
        $regex = '/hd_src_no_ratelimit:"([^"]+)"/';
        if (preg_match($regex, $curl_content, $match)) {
            return $match[1];
        } else if (preg_match('/hd_src:"([^"]+)"/', $curl_content, $match)) {
            return $match[1];
        }
    }

    function sd_link($curl_content)
    {
        $regex = '/sd_src_no_ratelimit:"([^"]+)"/';
        if (preg_match($regex, $curl_content, $match)) {
            return $match[1];
        } else {
            $mobil_link = $this->mobil_link($curl_content);
            if (!empty($mobil_link)) {
                return $mobil_link;
            }
        }
    }

    function get_title($curl_content)
    {
        $og_title = get_string_between($curl_content, 'property="og:title" content="', '"');
        $page_title = get_string_between($curl_content, '<title id="pageTitle">', '</title>');
        if (!empty($og_title)) {
            return $og_title;
        } else if (!empty($page_title)) {
            return $page_title;
        } else {
            return "Facebook Video";
        }
    }

    function get_thumbnail($curl_content)
    {
        if (preg_match('/og:image"\s*content="([^"]+)"/', $curl_content, $match)) {
            return $match[1];
        } elseif (preg_match('@<meta property="twitter:image" content="(.*?)" />@si', $curl_content, $match)) {
            return $match[1];
        }
    }

    function get_duration($curl_content)
    {
        if (preg_match('@<img class="_4lpf" src="(.*?)" />@si', $curl_content, $match)) {
            return $match[1];
        }
    }
}