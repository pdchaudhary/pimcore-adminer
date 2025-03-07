<?php

namespace CORS\Bundle\AdminerBundle\lib\Pim;

use Pimcore;
use Pimcore\Cache;
use Pimcore\Config;
use Pimcore\Localization\LocaleServiceInterface;
use Pimcore\Tool;
use Symfony\Component\HttpFoundation\Request;


/**
 *
 *
 * @author Blackbit digital Commerce GmbH <info@blackbit.de>
 * @copyright Blackbit digital Commerce GmbH, http://www.blackbit.de/
 */
class Helper
{
    /** @var Request */
    private static $request;

    private static $hostUrl;

    /**
     * @return Request
     */
    public static function getRequest() {
        if(self::$request === null) {
            $requestStack = \Pimcore::getContainer()->get('request_stack');
            self::$request = method_exists($requestStack, 'getMainRequest') ? $requestStack->getMainRequest() : $requestStack->getMasterRequest();
            if(!self::$request instanceof Request) {
                self::$request = new Request([], [], [], [], [], ['HTTPS' => 'on', 'SERVER_PORT' => 443, 'REQUEST_TIME_FLOAT' => time()]);
                self::$request->setLocale(\Pimcore::getContainer()->get(LocaleServiceInterface::class)->getLocale() ?? Tool::getDefaultLanguage());
                $requestStack->push(self::$request);
            } else {
                self::$request->setLocale(\Pimcore::getContainer()->get(LocaleServiceInterface::class)->getLocale() ?? Tool::getDefaultLanguage());
            }

            self::$request->attributes->set(Pimcore\Http\Request\Resolver\PimcoreContextResolver::ATTRIBUTE_PIMCORE_CONTEXT, 'webservice');
            self::$request->attributes->set(Pimcore\Http\RequestHelper::ATTRIBUTE_FRONTEND_REQUEST, false);
            self::$request->attributes->set('transfer', new \stdClass());

            $hostUrl = self::getHostUrl();
            if($hostUrl) {
                $context = \Pimcore::getContainer()->get('router')->getContext();
                $context->setHost(parse_url($hostUrl, PHP_URL_HOST));
                $context->setScheme(parse_url($hostUrl, PHP_URL_SCHEME));
            }
        }

        return self::$request;
    }

    public static function saveInCache($key, $value, array $tags = []) {
        $cacheEnabled = Pimcore\Cache::isEnabled();
        if(!$cacheEnabled) {
            Pimcore\Cache::enable();
        }

        Cache::save($value, $key, $tags, null, 0, true);

        if(!$cacheEnabled) {
            Pimcore\Cache::disable();
        }
    }

    public static function getFromCache($key) {
        $cacheEnabled = Pimcore\Cache::isEnabled();
        if (!$cacheEnabled) {
            Pimcore\Cache::enable();
        }

        $cacheValue = Cache::load($key);

        if (!$cacheEnabled) {
            Pimcore\Cache::disable();
        }

        return $cacheValue;
    }

    public static function getPimcoreSystemConfiguration($offset = null) {
        if (method_exists(Config::class, 'getSystemConfiguration')) {
            $config = Config::getSystemConfiguration();
        }

        if($offset) {
            return $config[$offset];
        }

        return $config;
    }

    public static function getHostUrl() {
        if(self::$hostUrl === null) {
            $protocol = self::getRequest()->getScheme() === 'http' ? 'http' : 'https';
            if($protocol === 'http') {
                foreach(['x-forwarded-proto', 'x-forwarded-scheme'] as $httpHeader) {
                    if (strtolower(self::getRequest()->headers->get($httpHeader, '')) === 'https') {
                        $protocol = 'https';
                    }
                }
            }
            
            $port = '';
            if (!in_array(self::getRequest()->getPort(), [443, 80])) {
                $port = ':'.self::getRequest()->getPort();
            }

            $hostname = self::getRequest()->getHost();
            if ($hostname && $hostname !== 'localhost') {
                self::$hostUrl = $protocol.'://'.$hostname.$port;
                self::saveInCache('PIMCORE_HOSTURL', self::$hostUrl);
            } else {
                self::$hostUrl = self::getFromCache('PIMCORE_HOSTURL');

                if(!self::$hostUrl) {
                    $systemConfig = Helper::getPimcoreSystemConfiguration('general');
                    if (!empty($systemConfig['domain'])) {
                        $hostname = $systemConfig['domain'];
                        self::$hostUrl = $protocol.'://'.$hostname.$port;
                    }
                }
            }
        }

        return self::$hostUrl;
    }


}