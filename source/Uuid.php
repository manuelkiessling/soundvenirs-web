<?php

class Uuid
{
    public static function generate()
    {
        $context = $uuid = null;
        uuid_create(&$context);

        uuid_make($context, UUID_MAKE_V4);
        uuid_export($context, UUID_FMT_STR, &$uuid);

        return trim($uuid);
    }
}
