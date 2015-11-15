<?php

namespace common\widgets\SocialLinks;

use Yii;
use yii\base\Widget;

class ShareBar extends Widget
{
    public $title;
    public $media;
    public $url;
    
    // Email, Facebook, Github,  GooglePlus, Hackernews, LinkedIn, Pinterest, Pocket, Reddit, Tumblr, Twitter, Vk, Youtube 
    public $networks = [
        'Email',
        'Facebook',
        'Github',
        'GooglePlus',
        'Hackernews',
        'LinkedIn',
        'Pinterest',
        'Pocket',
        'Reddit',
        'Tumblr',
        'Twitter',
        'Vk',
        'Youtube'       
    ];

    public function init(){
		parent::init();
		if(!$this->title)
            $this->title = Yii::$app->name;
        if(!$this->media)
            $this->media = Yii::$app->urlManager->baseUrl.'/logo.png';
        if(!$this->url):
            $this->url = Yii::$app->request->absoluteUrl;
        else:
            $this->url = Yii::$app->urlManager->createAbsoluteUrl($this->url);
        endif;
	}

    /**
     * @return string
     */
    public function run()
    {
        Assets::register($this->getView());
        $views = '';
		foreach($this->networks as $network => $params){	  
          $views .= $this->render(strtolower($params), ['title' => $this->title, 'media' => $this->media, 'url' => $this->url], true);
		}   
        return $this->render('sharebar', ['views'=>$views]);
    }
}
