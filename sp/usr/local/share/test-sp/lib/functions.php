<?php
function display_table($array, $local_sp) {
    foreach ($array as $name => $id) {
        $key = $local_sp ? 'HTTP_' . strtoupper(str_replace('-', '_', $id)) : $id;
        if (isset($_SERVER[$key])) {
            $value = $_SERVER[$key];
        } else {
            continue;
        }
        printf(
            "<tr><td>%s</td><td>%s</td></tr>\n",
            $name,
            $value
        );
    }
}

function is_connected($local_sp) {
    return
        (
            !$local_sp                         &&
            isset($_SERVER['Shib-Session-ID']) &&
            !empty($_SERVER['Shib-Session-ID'])
        )
        ||
        (
            $local_sp                               &&
            isset($_SERVER['HTTP_SHIB_SESSION_ID']) &&
            !empty($_SERVER['HTTP_SHIB_SESSION_ID'])
        );
}

?>
