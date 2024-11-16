<?php
function hash_encode($data)
{
    $hash = base64_encode($data);
    return trim($hash, "==");
}

function hash_decode($hash)
{
    return base64_decode($hash) ? base64_decode($hash) : false;
}
