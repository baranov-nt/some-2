<?php

namespace common\widgets\SocialLinks;

/**
 * This is just an example.
 */
class Reddit extends \imanilchaudhari\rrssb\Widget
{
    
    public function run()
    {
        Assets::register();

        return "Hello!";
    }
}
