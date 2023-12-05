<?php
/**
 * @link https://cms.skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */

namespace skeeks\cms\mysqlSession;


use yii\base\Exception;
use yii\helpers\Json;
use yii\helpers\VarDumper;
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 */
class DbSession extends \yii\web\DbSession
{
    public $sessionTable = '{{%cms_session}}';

    /**
     * @var float|int
     */
    //public $timeout = 3600 * 24 * 365;

    /**
     * Не запускать сессию с ботами
     * @var bool
     */
    public $is_write_for_bot = false;


    public function init()
    {
        $this->writeCallback = function ($session) {

            /*$string = '';
            try {
                throw new Exception("1");
            } catch (\Exception $e) {
                $string = VarDumper::dumpAsString($e);
            }*/

            $ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : null;
            if ($ip !== null) {
                $ips = explode(",", $ip);
                $ip = \yii\helpers\ArrayHelper::getValue($ips, "0");
            }
            return [
                'cms_user_id'      => \Yii::$app->user->id,
                'cms_site_id'      => \Yii::$app->skeeks->site ? \Yii::$app->skeeks->site->id : null,
                'ip'               => $ip,
                'https_user_agent' => isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : null,
                'updated_at'       => time(),
                /*'controller'       => \Yii::$app->controller ? \Yii::$app->controller->uniqueId : '',
                'ref'       => \Yii::$app->request->referrer ? \Yii::$app->request->referrer : "",
                'url'       => \Yii::$app->request->absoluteUrl,
                'server'       => Json::encode($_SERVER),
                'cookie'       => Json::encode($_COOKIE),*/
                //'stack'       => $string,
            ];
        };

        parent::init();
    }

    /*public function openSession($savePath, $sessionName)
    {

        //Если бот то не записываем сессию
        /*if ($this->isBot()) {
            return true;
        }

        if ($this->_isNoStartSessionByRequest()) {
            return true;
        }*/

        /*if (YII_ENV_DEV) {
            print_r(\Yii::$app->controller->uniqueId);die;
            throw new Exception("111");
            var_dump($this->getUseStrictMode());
            die;



        return parent::openSession($savePath, $sessionName);
    }*/


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
        if ($this->isBot()) {
            return true;
        }

        if ($this->_isNoStartSessionByRequest()) {
            return true;
        }

        return parent::writeSession($id, $data);
    }

    /**
     * На этих действиях не нужно сохранять сессию
     * @return bool
     */
    protected function _isNoStartSessionByRequest()
    {
        if (\Yii::$app->controller && in_array(\Yii::$app->controller->uniqueId, [
                'seo/robots',
                'cms/favicon',
                'cms/online',
                'cms/image-preview',

            ])) {
            return true;
        }

        return false;
    }

    public function isBot()
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
            'Lighthouse',
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
            'StormCrawler',
            'GeedoBot',
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