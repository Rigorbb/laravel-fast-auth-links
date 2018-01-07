<?php

namespace Rigorbb\FastAuthLinks;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class FastAuthLink {

    /**
     * @var
     */
    private $salt;

    const FAST_AUTH_LINK_HOURLY = 'hour';
    const FAST_AUTH_LINK_DAILY = 'day';
    const FAST_AUTH_LINK_MONTHLY = 'month';

    /**
     * Param name in url
     *
     * @var string
     */
    private $paramKey = 'fa_hash';

    /**
     * FastAuthLink constructor.
     * @param $salt
     */
    public function __construct($salt)
    {
        $this->salt = $salt;
    }

    /**
     * @param $link
     * @return string
     */
    public function hourly($link, $user)
    {
        return $this->addAuthParam($link, $user, static::FAST_AUTH_LINK_HOURLY);
    }

    /**
     * @param $link
     * @return string
     */
    public function daily($link, $user)
    {
        return $this->addAuthParam($link, $user, static::FAST_AUTH_LINK_DAILY);
    }

    /**
     * @param $link
     * @return string
     */
    public function monthly($link, $user)
    {
        return $this->addAuthParam($link, $user, static::FAST_AUTH_LINK_MONTHLY);
    }

    /**
     * Add auth hash param in url by type
     *
     * @param $link
     * @param $user
     * @param $type
     * @return string
     */
    protected function addAuthParam($link, $user, $type)
    {
        $hash = $this->hash($link, $type, $user->id) . '|' . $user->id;

        $query = parse_url($link, PHP_URL_QUERY);

        if ($query) {
            $link .= '&' . $this->paramKey .'=' . $hash;
        } else {
            $link .= '?' . $this->paramKey .'=' . $hash;
        }

        return $link;
    }

    /**
     * Generate hash by type and link
     *
     * @param $link
     * @param $type
     * @param $userId
     * @return string
     */
    protected function hash($link, $type, $userId)
    {
        return sha1($link . $this->salt . $userId .$this->getDateByType($type));
    }

    /**
     * @param $type
     * @return false|string
     */
    protected function getDateByType($type)
    {
        switch ($type) {
            case static::FAST_AUTH_LINK_DAILY:
                return  Carbon::now()->format('Ymd');
            case static::FAST_AUTH_LINK_HOURLY:
                return Carbon::now()->format('Ymdh');
            case static::FAST_AUTH_LINK_MONTHLY:
                return Carbon::now()->format('Ym');
        }

        throw new \InvalidArgumentException('Unknown type');
    }

    /**
     * Check the link with hash
     * Return true if hash is correct and active
     *
     * @param $link
     * @return bool
     */
    public function checkLink($link)
    {
        $possibleTypes = [
            static::FAST_AUTH_LINK_MONTHLY,
            static::FAST_AUTH_LINK_DAILY,
            static::FAST_AUTH_LINK_HOURLY,
        ];

        $hash = $this->parseHash($link);

        if (empty($hash)) {
            return false;
        }

        list($hash, $userId) = explode("|", $hash);

        $linkWithoutHash = $this->getLinkWithoutHash($link);

        foreach ($possibleTypes as $date) {
            if ($this->hash($linkWithoutHash, $date, $userId) === $hash) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param $link
     * @return mixed
     */
    protected function parseHash($link)
    {
        if (!stristr($link, $this->paramKey . '=')) {
            return null;
        }

        $link = parse_url($link);
        parse_str($link['query'], $result);

        return urldecode($result[$this->paramKey]);
    }

    /**
     * Remove hash param from link
     *
     * @param $link
     * @return string
     */
    protected function getLinkWithoutHash($link)
    {
        $params = parse_url($link)['query'];

        parse_str($params, $result);

        $param = $this->paramKey . '=' . $result[$this->paramKey];
        $param = str_replace('|', '%7C', $param);

        if (count($params) === 1) {
            $param = '?' . $param;
        } else {
            $param = '&' . $param;
        }

        $url = str_replace($param, '', $link);

        return $url;
    }

    /**
     * @param $url
     * @return bool
     */
    public function authByHash($url)
    {
        list(,$userId) = explode('|', $this->parseHash($url));

        Auth::loginUsingId($userId, true);

        return true;
    }
}