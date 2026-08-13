<?php
$mh = curl_multi_init();
$ch1 = curl_init("https://httpbin.org/get");
curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
curl_multi_add_handle($mh, $ch1);
do {
    $status = curl_multi_exec($mh, $active);
    if ($active) {
        curl_multi_select($mh);
    }
} while ($active && $status == CURLM_OK);
echo curl_multi_getcontent($ch1);
curl_multi_remove_handle($mh, $ch1);
curl_multi_close($mh);
