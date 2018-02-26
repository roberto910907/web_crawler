<?php
/**
 * @author  Roberto Rielo <roberto910907@gmail.com>.
 *
 * @version Crawler v1.0 21/02/18 06:02 PM
 */
namespace App\Client;

use GuzzleHttp\Client as BaseClient;
use Psr\Http\Message\StreamInterface;

class Client extends BaseClient
{
    const URL = 'https://news.ycombinator.com/';

    /**
     * Retrieve the body of the response.
     *
     * @param string $url
     *
     * @return StreamInterface
     */
    public function getSiteContent($url = self::URL)
    {
        return $this->get($url)->getBody();
    }

    /**
     * Retrieve the html string of the page.
     *
     * @param string $url
     *
     * @return string
     */
    public function getHtmlContentAsString($url = self::URL)
    {
        return $this->getSiteContent($url)->getContents();
    }
}