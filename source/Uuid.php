<?php

class Uuid
{
    public static function generate()
    {
        return sha1(uniqid('', true));
    }
}
