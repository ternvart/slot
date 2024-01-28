<?php
function get_domain($url)
{
    $pieces = parse_url($url);
    $domain = isset($pieces['host']) ? $pieces['host'] : '';
    if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)) {
        return $regs['domain'];
    }
    return false;
}

function build_menu($footer = false)
{
    $menu = json_decode(option("theme.menu"), true);
    if (!empty($menu)) {
        foreach ($menu as $node) {
            if (!empty($node['title']) && !empty($node['url'])) {
                if ($footer === true) {
                    echo '<li><a target="_blank" href="' . $node['url'] . '">' . $node['title'] . '</a></li>';
                } else {
                    echo '<li class="nav-item"><a target="_blank" class="nav-link" href="' . $node['url'] . '">' . $node['title'] . '</a></li>';
                }
            }
        }
    }
}

function social_links()
{
    $social_links = json_decode(option("theme.general"), true);
    foreach ($social_links as $link => $key) {
        if (!empty($key)) {
            switch ($link) {
                case 'facebook':
                    echo '<a class="btn btn-sm btn-social btn-fill btn-facebook" href="https://facebook.com/' . $key . '"><i class="fab fa-facebook-f"></i></a>';
                    break;
                case 'twitter':
                    echo '<a class="btn btn-sm btn-social btn-fill btn-twitter" href="https://twitter.com/' . $key . '"><i class="fab fa-twitter"></i></a>';
                    break;
                case 'youtube':
                    echo '<a class="btn btn-sm btn-social btn-fill btn-youtube" href="https://youtube.com/' . $key . '"><i class="fab fa-youtube"></i></a>';
                    break;
                case 'instagram':
                    echo '<a class="btn btn-sm btn-social btn-fill btn-instagram" href="https://instagram.com/' . $key . '"><i class="fab fa-instagram"></i></a>';
                    break;
            }
        }
    }
}

function get_country_flag($country_code)
{
    switch ($country_code) {
        case "en":
            $icon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><circle cx="256" cy="256" r="256" fill="#f0f0f0"/><g fill="#0052b4"><path d="M52.92 100.142c-20.109 26.163-35.272 56.318-44.101 89.077h133.178L52.92 100.142zM503.181 189.219c-8.829-32.758-23.993-62.913-44.101-89.076l-89.075 89.076h133.176zM8.819 322.784c8.83 32.758 23.993 62.913 44.101 89.075l89.074-89.075H8.819zM411.858 52.921c-26.163-20.109-56.317-35.272-89.076-44.102v133.177l89.076-89.075zM100.142 459.079c26.163 20.109 56.318 35.272 89.076 44.102V370.005l-89.076 89.074zM189.217 8.819c-32.758 8.83-62.913 23.993-89.075 44.101l89.075 89.075V8.819zM322.783 503.181c32.758-8.83 62.913-23.993 89.075-44.101l-89.075-89.075v133.176zM370.005 322.784l89.075 89.076c20.108-26.162 35.272-56.318 44.101-89.076H370.005z"/></g><g fill="#d80027"><path d="M509.833 222.609H289.392V2.167A258.556 258.556 0 00256 0c-11.319 0-22.461.744-33.391 2.167v220.441H2.167A258.556 258.556 0 000 256c0 11.319.744 22.461 2.167 33.391h220.441v220.442a258.35 258.35 0 0066.783 0V289.392h220.442A258.533 258.533 0 00512 256c0-11.317-.744-22.461-2.167-33.391z"/><path d="M322.783 322.784L437.019 437.02a256.636 256.636 0 0015.048-16.435l-97.802-97.802h-31.482v.001zM189.217 322.784h-.002L74.98 437.019a256.636 256.636 0 0016.435 15.048l97.802-97.804v-31.479zM189.217 189.219v-.002L74.981 74.98a256.636 256.636 0 00-15.048 16.435l97.803 97.803h31.481zM322.783 189.219L437.02 74.981a256.328 256.328 0 00-16.435-15.047l-97.802 97.803v31.482z"/></g></svg>';
            break;
        case "tr":
            $icon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><circle cx="256" cy="256" r="256" fill="#d80027"/><g fill="#f0f0f0"><path d="M245.518 209.186l21.005 28.945 34.017-11.03-21.038 28.92 21.002 28.944-34.005-11.072-21.037 28.92.022-35.761-34.006-11.072 34.018-11.03z"/><path d="M188.194 328.348c-39.956 0-72.348-32.392-72.348-72.348s32.392-72.348 72.348-72.348c12.458 0 24.18 3.151 34.414 8.696-16.055-15.702-38.012-25.392-62.24-25.392-49.178 0-89.043 39.866-89.043 89.043s39.866 89.043 89.043 89.043c24.23 0 46.186-9.691 62.24-25.392-10.234 5.547-21.956 8.698-34.414 8.698z"/></g></svg>';
            break;
        default:
            $icon = '';
            break;
    }
    return $icon;
}

function get_language_name($country_code)
{
    switch ($country_code) {
        case "en":
            $name = "English";
            break;
        case "tr":
            $name = "Türkçe";
            break;
        default:
            $name = strtoupper($country_code);
            break;
    }
    return $name;
}

function list_languages()
{
    foreach (glob(__DIR__ . "/../../language/*.php") as $filename) {
        if (basename($filename) != "index.php") {
            $language = str_replace(".php", null, basename($filename));
            if (language_exists($language) === true) {
                $flag = sprintf('<span class="country-flag mr-1 mt-1">%s</span>', get_country_flag($language));
                printf('<a class="dropdown-item" href="?lang=%s">%s %s</a>', $language, $flag, get_language_name($language));
                //echo '<a class="dropdown-item" href="?lang=' . $language . '">' . strtoupper($language) . '</a>';
            }
        }
    }
}

function get_supported_websites()
{
    // Fill slug and text values for change default ones
    $websites = array(
        array("name" => "9gag", "color" => "#000000", "slug" => "", "text" => "", "type" => "video"),
        array("name" => "bandcamp", "color" => "#21759b", "slug" => "", "text" => "", "type" => "music"),
        array("name" => "blogger", "color" => "#fc4f08", "slug" => "", "text" => "", "type" => "video"),
        array("name" => "break", "color" => "#b92b27", "slug" => "", "text" => "", "type" => "video"),
        array("name" => "buzzfeed", "color" => "#df2029", "slug" => "", "text" => "", "type" => "video"),
        array("name" => "dailymotion", "color" => "#0077b5", "slug" => "", "text" => "", "type" => "video"),
        array("name" => "espn", "color" => "#df2029", "slug" => "", "text" => "", "type" => "video"),
        array("name" => "facebook", "color" => "#3b5998", "slug" => "", "text" => "", "type" => "video"),
        array("name" => "flickr", "color" => "#ff0084", "slug" => "", "text" => "", "type" => "video"),
        array("name" => "imdb", "color" => "#e8c700", "slug" => "", "text" => "", "type" => "video"),
        array("name" => "imgur", "color" => "#02b875", "slug" => "", "text" => "", "type" => "video"),
        array("name" => "instagram", "color" => "#e4405f", "slug" => "", "text" => "", "type" => "video"),
        array("name" => "izlesene", "color" => "#ff6600", "slug" => "", "text" => "", "type" => "video"),
        array("name" => "likee", "color" => "#be3cfa", "slug" => "", "text" => "", "type" => "video"),
        array("name" => "liveleak", "color" => "#dd4b39", "slug" => "", "text" => "", "type" => "video"),
        array("name" => "mashable", "color" => "#0084ff", "slug" => "", "text" => "", "type" => "video"),
        array("name" => "odnoklassniki", "color" => "#f57d00", "slug" => "", "text" => "", "type" => "video"),
        array("name" => "pinterest", "color" => "#bf1f24", "slug" => "", "text" => "", "type" => "video"),
        array("name" => "reddit", "color" => "#ff4301", "slug" => "", "text" => "", "type" => "video"),
        array("name" => "soundcloud", "color" => "#ff3300", "slug" => "", "text" => "", "type" => "music"),
        array("name" => "ted", "color" => "#e62b1e", "slug" => "", "text" => "", "type" => "video"),
        array("name" => "tiktok", "color" => "#131418", "slug" => "", "text" => "", "type" => "video"),
        array("name" => "tumblr", "color" => "#32506d", "slug" => "", "text" => "", "type" => "video"),
        array("name" => "twitch", "color" => "#6441a5", "slug" => "", "text" => "", "type" => "clip"),
        array("name" => "twitter", "color" => "#00aced", "slug" => "", "text" => "", "type" => "video"),
        array("name" => "vimeo", "color" => "#1ab7ea", "slug" => "", "text" => "", "type" => "video"),
        array("name" => "vk", "color" => "#4a76a8", "slug" => "", "text" => "", "type" => "video"),
        array("name" => "youtube", "color" => "#d82624", "slug" => "", "text" => "", "type" => "video"),
    );
    return $websites;
}