<?php
function PostcodeCheck($postcode)
{
    $remove = str_replace(" ","", $postcode);
    $upper = strtoupper($remove);

    if( preg_match("/^\b[1-9]\d{3}\s*[A-Z]{2}\b$/",  $upper)) {
        return TRUE;
    } else {
        return FALSE;
    }
}
PostcodeCheck("9074 AES");
