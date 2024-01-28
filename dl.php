<?php
require_once __DIR__ . "/system/config.php";
ini_set('max_execution_time', '0');
if (!empty($_GET["source"]) && !empty($_GET["dl"])) {
    $current_result = $_SESSION['result'][$_SESSION["token"]];
    $i = (int)base64_decode($_GET["dl"]);
    if (isset($current_result["links"][$i]["url"]) != "" && is_numeric($i ?? "")) {
        if (isset($config["bandwidth_saving"]) != "") {
            redirect($current_result["links"][$i]["url"]);
        } else {
            if (!empty($config["download_suffix"])) {
                $config["download_suffix"] = "-" . $config["download_suffix"];
            }
            $parsed_remote_url = parse_url($current_result["links"][$i]["url"]);
            $remote_domain = str_ireplace("www.", "", $parsed_remote_url["host"]);
            $local_domain = str_ireplace("www.", "", parse_url($config["url"], PHP_URL_HOST));
            if ($local_domain == $remote_domain) {
                force_download_legacy(__DIR__ . $parsed_remote_url['path'], $current_result["title"] . $config["download_suffix"], $current_result["links"][$i]["type"]);
            } else {
                force_download($current_result["links"][$i]["url"], $current_result["title"] . $config["download_suffix"], $current_result["links"][$i]["type"]);
            }
        }
    } else {
        http_response_code("404");
    }
} else {
    http_response_code("404");
}