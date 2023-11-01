<?php

require_once '/Users/macbook/Desktop/valet/clientChat/fns/minify/load.php';
use MatthiasMullie\Minify;


foreach (glob("assets/js/combined_js_"."*.*") as $old_cache_file) {
    unlink($old_cache_file);
}

$js_files = [
    '/Users/macbook/Desktop/valet/clientChat/assets/thirdparty/jquery/jquery-min.js',
    '/Users/macbook/Desktop/valet/clientChat/assets/thirdparty/jquery/jquery-after-load.js',
    '/Users/macbook/Desktop/valet/clientChat/assets/thirdparty/bootstrap/bootstrap.bundle.min.js',
    '/Users/macbook/Desktop/valet/clientChat/assets/thirdparty/jquery.lazy/jquery.lazy.min.js',
    '/Users/macbook/Desktop/valet/clientChat/assets/thirdparty/jquery.marquee/jquery.marquee.min.js',
    '/Users/macbook/Desktop/valet/clientChat/assets/thirdparty/recordrtc/recordrtc.min.js',
    '/Users/macbook/Desktop/valet/clientChat/assets/thirdparty/is-lockdown/is-lockdown.js'
];

$minifier = new Minify\JS();
$minifier->add($js_files);

$minifiedPath = '/Users/macbook/Desktop/valet/clientChat/assets/js/combined_js_chat_page_libraries.js';
$minifier->minify($minifiedPath);

$js_files = [
    '/Users/macbook/Desktop/valet/clientChat/assets/thirdparty/colorpicker/dist/js/bootstrap-colorpicker.min.js',
    '/Users/macbook/Desktop/valet/clientChat/assets/thirdparty/summernote/summernote.min.js',
    '/Users/macbook/Desktop/valet/clientChat/assets/thirdparty/summernote/summernote-image-attributes.js'
];

$minifier = new Minify\JS();
$minifier->add($js_files);

$minifiedPath = '/Users/macbook/Desktop/valet/clientChat/assets/js/combined_js_chat_page_libraries_2.js';
$minifier->minify($minifiedPath);


$js_files = [
    '/Users/macbook/Desktop/valet/clientChat/assets/thirdparty/codemirror/lib/codemirror.js',
    '/Users/macbook/Desktop/valet/clientChat/assets/thirdparty/codemirror/mode/xml/xml.js',
    '/Users/macbook/Desktop/valet/clientChat/assets/thirdparty/codemirror/mode/javascript/javascript.js',
    '/Users/macbook/Desktop/valet/clientChat/assets/thirdparty/codemirror/mode/css/css.js',
    '/Users/macbook/Desktop/valet/clientChat/assets/thirdparty/codemirror/mode/clike/clike.js',
    '/Users/macbook/Desktop/valet/clientChat/assets/thirdparty/codemirror/mode/php/php.js',
    '/Users/macbook/Desktop/valet/clientChat/assets/thirdparty/codemirror/mode/htmlmixed/htmlmixed.js',
];

$minifier = new Minify\JS();
$minifier->add($js_files);

$minifiedPath = '/Users/macbook/Desktop/valet/clientChat/assets/js/combined_js_chat_page_libraries_3.js';
$minifier->minify($minifiedPath);


$js_files = [
    '/Users/macbook/Desktop/valet/clientChat/assets/js/chat_page/draggable_element.js',
    '/Users/macbook/Desktop/valet/clientChat/assets/js/chat_page/main.js',
    '/Users/macbook/Desktop/valet/clientChat/assets/js/chat_page/aside.js',
    '/Users/macbook/Desktop/valet/clientChat/assets/js/chat_page/middle.js',
    '/Users/macbook/Desktop/valet/clientChat/assets/js/chat_page/form.js',
    '/Users/macbook/Desktop/valet/clientChat/assets/js/chat_page/audio_player.js',
    '/Users/macbook/Desktop/valet/clientChat/assets/js/chat_page/info_box.js',
    '/Users/macbook/Desktop/valet/clientChat/assets/js/chat_page/grid_list.js',
    '/Users/macbook/Desktop/valet/clientChat/assets/js/chat_page/audio_message.js',
    '/Users/macbook/Desktop/valet/clientChat/assets/js/chat_page/messages.js',
    '/Users/macbook/Desktop/valet/clientChat/assets/js/chat_page/message_editor.js',
    '/Users/macbook/Desktop/valet/clientChat/assets/js/chat_page/geolocation_system.js',
    '/Users/macbook/Desktop/valet/clientChat/assets/js/chat_page/statistics.js',
    '/Users/macbook/Desktop/valet/clientChat/assets/js/chat_page/membership_info.js',
    '/Users/macbook/Desktop/valet/clientChat/assets/js/common/api_request.js',
    '/Users/macbook/Desktop/valet/clientChat/assets/js/chat_page/realtime.js',
    '/Users/macbook/Desktop/valet/clientChat/assets/js/common/custom_js.js',
    '/Users/macbook/Desktop/valet/clientChat/assets/js/chat_page/custom_js.js',
];

$minifier = new Minify\JS();
$minifier->add($js_files);

$minifiedPath = '/Users/macbook/Desktop/valet/clientChat/assets/js/combined_js_chat_page.js';
$minifier->minify($minifiedPath);


$js_files = [
    '/Users/macbook/Desktop/valet/clientChat/assets/js/chat_page/emojis.js',
    '/Users/macbook/Desktop/valet/clientChat/assets/thirdparty/videojs/video.min.js',
    '/Users/macbook/Desktop/valet/clientChat/assets/thirdparty/viewerjs/viewer.min.js',
    '/Users/macbook/Desktop/valet/clientChat/assets/thirdparty/viewerjs/jquery-viewer.js',
    '/Users/macbook/Desktop/valet/clientChat/assets/thirdparty/videojs/youtube.min.js',
    '/Users/macbook/Desktop/valet/clientChat/assets/thirdparty/videojs/dailymotion.min.js',
    '/Users/macbook/Desktop/valet/clientChat/assets/thirdparty/videojs/vimeo.min.js',
];

$minifier = new Minify\JS();
$minifier->add($js_files);

$minifiedPath = '/Users/macbook/Desktop/valet/clientChat/assets/js/combined_js_chat_page_after_load.js';
$minifier->minify($minifiedPath);


$js_files = [
    '/Users/macbook/Desktop/valet/clientChat/assets/thirdparty/jquery/jquery-min.js',
    '/Users/macbook/Desktop/valet/clientChat/assets/thirdparty/bootstrap/bootstrap.bundle.min.js',
    '/Users/macbook/Desktop/valet/clientChat/assets/thirdparty/intl-tel-input/js/intlTelInput.js'
];

$minifier = new Minify\JS();
$minifier->add($js_files);

$minifiedPath = '/Users/macbook/Desktop/valet/clientChat/assets/js/combined_js_entry_page_libraries.js';
$minifier->minify($minifiedPath);


$js_files = [
    '/Users/macbook/Desktop/valet/clientChat/assets/js/entry_page/script.js',
    '/Users/macbook/Desktop/valet/clientChat/assets/js/common/api_request.js',
    '/Users/macbook/Desktop/valet/clientChat/assets/js/common/custom_js.js',
    '/Users/macbook/Desktop/valet/clientChat/assets/js/entry_page/custom_js.js',
];

$minifier = new Minify\JS();
$minifier->add($js_files);

$minifiedPath = '/Users/macbook/Desktop/valet/clientChat/assets/js/combined_js_entry_page.js';
$minifier->minify($minifiedPath);


$js_files = [
    '/Users/macbook/Desktop/valet/clientChat/assets/thirdparty/jquery/jquery-min.js',
    '/Users/macbook/Desktop/valet/clientChat/assets/thirdparty/bootstrap/bootstrap.bundle.min.js',
    '/Users/macbook/Desktop/valet/clientChat/assets/thirdparty/is-lockdown/is-lockdown.js'
];

$minifier = new Minify\JS();
$minifier->add($js_files);

$minifiedPath = '/Users/macbook/Desktop/valet/clientChat/assets/js/combined_js_landing_page_libraries.js';
$minifier->minify($minifiedPath);

$js_files = [
    '/Users/macbook/Desktop/valet/clientChat/assets/js/landing_page/script.js',
    '/Users/macbook/Desktop/valet/clientChat/assets/js/common/api_request.js',
    '/Users/macbook/Desktop/valet/clientChat/assets/js/common/custom_js.js',
    '/Users/macbook/Desktop/valet/clientChat/assets/js/landing_page/custom_js.js',
];

$minifier = new Minify\JS();
$minifier->add($js_files);

$minifiedPath = '/Users/macbook/Desktop/valet/clientChat/assets/js/combined_js_landing_page.js';
$minifier->minify($minifiedPath);

$result = true;