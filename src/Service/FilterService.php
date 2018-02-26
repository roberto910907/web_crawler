<?php
/**
 * @author  Roberto Rielo <roberto910907@gmail.com>.
 * @version Crawler v1.0 25/02/18 08:03 PM
 */

namespace App\Service;

use App\Client\Client;
use App\Model\FilterModel;
use App\Model\NewsCollection;
use App\Crawler\Crawler;

class FilterService
{
    const NEWS_NUMBER = 30;

    private $crawler;
    private $newsCollection;

    public function __construct(Client $client)
    {
        $this->crawler = new Crawler($client->getHtmlContentAsString());
        $this->newsCollection = new NewsCollection($this->convertRowsToArray());
    }

    public function applyFilters(FilterModel $filterModel = null)
    {
        if (!$filterModel) {
            return $this->newsCollection->toArray();
        }

        return $this->newsCollection->applyFilters($filterModel);
    }

    public function convertRowsToArray($filterNews = self::NEWS_NUMBER)
    {
        $newList = [];

        foreach ($this->crawler->filter('table.itemlist tr:not(.spacer):not(.morespace)') as $index => $node) {
            $nodeElement = new Crawler($node);

            if ($index === $filterNews * 2) {
                return $newList;
            }

            if ($node->hasAttribute('class')) {
                $newList[$index] = [
                    'title' => $nodeElement->filter('.storylink')->text(),
                    'number' => str_replace('.', '', $nodeElement->filter('.rank')->text())
                ];
            } else {
                $newList[$index - 1] = array_merge($newList[$index - 1], [
                    'comments' => (int)$nodeElement->filter('.score')->text(),
                    'points' => (int)$nodeElement->filter('a')->last()->text()
                ]);
            }
        }

        return $newList;
    }
}