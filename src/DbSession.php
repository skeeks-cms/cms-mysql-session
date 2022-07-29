<?php
/**
 * @link https://cms.skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */

namespace skeeks\cms\mysqlSession;


/**
 * @author Semenov Alexander <semenov@skeeks.com>
 */
class DbSession extends \yii\web\DbSession
{
    public $sessionTable = '{{%cms_session}}';

    /**
     * @var float|int
     */
    public $timeout = 3600 * 24 * 365;

    /**
     * Не запускать сессию с ботами
     * @var bool
     */
    public $is_write_for_bot = false;

    public function init()
    {
        $this->writeCallback = function ($session) {
            return [
                'cms_user_id'      => \Yii::$app->user->id,
                'cms_site_id'      => \Yii::$app->skeeks->site ? \Yii::$app->skeeks->site->id : null,
                'ip'               => isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : null,
                'https_user_agent' => isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : null,
                'updated_at'       => time(),
            ];
        };

        parent::init();
    }


    /**
     * @param string $id
     * @param string $data
     * @return bool
     */
    public function writeSession($id, $data)
    {
        //Если для ботов можно записывать сессию тогда ничего не делаем
        if ($this->is_write_for_bot === true) {
            return parent::writeSession($id, $data);
        }

        //Если бот то не записываем сессию
        if ($this->_isBot()) {
            return true;
        }

        return parent::writeSession($id, $data);
    }

    protected function _isBot()
    {
        /* Эта функция будет проверять, является ли посетитель роботом поисковой системы */
        $bots = [
            'serpstatbot',
            'CCBot',
            'MolokaiBot',
            'komodia',
            'applebot',
            'BLEXBot',
            'dotbot',
            'mj12bot',
            'petalbot',
            'SemrushBot',
            'AhrefsBot',
            'SeoBot',
            'rambler',
            'googlebot',
            'aport',
            'yahoo',
            'msnbot',
            'turtle',
            'mail.ru',
            'omsktele',
            'yetibot',
            'picsearch',
            'sape.bot',
            'sape_context',
            'gigabot',
            'snapbot',
            'alexa.com',
            'megadownload.net',
            'askpeter.info',
            'igde.ru',
            'ask.com',
            'qwartabot',
            'yanga.co.uk',
            'scoutjet',
            'similarpages',
            'oozbot',
            'shrinktheweb.com',
            'aboutusbot',
            'followsite.com',
            'dataparksearch',
            'google-sitemaps',
            'appEngine-google',
            'feedfetcher-google',
            'liveinternet.ru',
            'xml-sitemaps.com',
            'agama',
            'metadatalabs.com',
            'h1.hrn.ru',
            'googlealert.com',
            'seo-rus.com',
            'yaDirectBot',
            'yandeG',
            'yandex',
            'yandexSomething',
            'Copyscape.com',
            'AdsBot-Google',
            'domaintools.com',
            'Nigma.ru',
            'bing.com',
            'dotnetdotcom',
        ];
        foreach ($bots as $bot) {
            if (stripos($_SERVER['HTTP_USER_AGENT'], $bot) !== false) {
                $botname = $bot;
                return true;
            }
        }
        return false;
    }
}