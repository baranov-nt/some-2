<?php

namespace common\widgets\SocialLinks;

/**
 * This is just an example.
 */
class Pocket extends \imanilchaudhari\rrssb\Widget
{
    
    public function run()
    {
        Assets::register();

        return "Hello!";
    }
}
