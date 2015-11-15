<?php

namespace common\widgets\SocialLinks;

/**
 * This is just an example.
 */
class Tumblr extends \imanilchaudhari\rrssb\Widget
{
    
    public function run()
    {
        Assets::register();

        return "Hello!";
    }
}
