<?php
/**
 * Description of NewsCollection
 *
 * @author  Roberto Rielo <roberto910907@gmail.com>
 * @version Crawler v1.0 25/02/18 08:03 PM
 */

namespace App\Model;

use App\Collection\ArrayCollection;
use App\Helper\StringHelper;

class NewsCollection extends ArrayCollection
{
    /**
     * @param array $elements
     */
    public function __construct(array $elements = array())
    {
        parent::__construct($elements);
    }

    public function applyFilters(FilterModel $filterModel = null)
    {
        return $this->filterByWords($filterModel)->orderBy($filterModel);
    }

    /**
     * @param FilterModel $filterModel
     *
     * @return NewsCollection
     */
    public function filterByWords(FilterModel $filterModel)
    {
        $wordsAmmount = $filterModel->getWords();

        switch ($filterModel->getOperator()) {
            case '<':
                return $this->filterLessThanWords($wordsAmmount);
                break;
            case '>':
                return $this->filterGreaterThanWords($wordsAmmount);
                break;
            case '<=':
                return $this->filterLessThanOrEqualWords($wordsAmmount);
                break;
            case '>=':
                return $this->filterGreaterThanOrEqualWords($wordsAmmount);
                break;
            default:
                return new NewsCollection();
        }
    }

    public function filterLessThanWords($wordsAmmount)
    {
        return $this->filter(function ($newArray) use ($wordsAmmount) {
            return StringHelper::countWords($newArray['title']) < $wordsAmmount;
        });
    }

    public function filterLessThanOrEqualWords($wordsAmmount)
    {
        return $this->filter(function ($newArray) use ($wordsAmmount) {
            return StringHelper::countWords($newArray['title']) <= $wordsAmmount;
        });
    }

    public function filterGreaterThanWords($wordsAmmount)
    {
        return $this->filter(function ($newArray) use ($wordsAmmount) {
            return StringHelper::countWords($newArray['title']) > $wordsAmmount;
        });
    }

    public function filterGreaterThanOrEqualWords($wordsAmmount)
    {
        return $this->filter(function ($newArray) use ($wordsAmmount) {
            return StringHelper::countWords($newArray['title']) >= $wordsAmmount;
        });
    }

    public function orderBy(FilterModel $filterModel)
    {
        $iterator = $this->getIterator();

        $iterator->uasort(function ($first, $second) use ($filterModel) {
            return $first[$filterModel->getOrderBy()] <=> $second[$filterModel->getOrderBy()];
        });

        return $iterator->getArrayCopy();
    }

    /**
     * Converts this object into string representation.
     *
     * @return string
     */
    public function __toString()
    {
        return __CLASS__ . '@' . spl_object_hash($this);
    }

    /**
     * Gets the PHP array representation of this collection.
     *
     * Same as (alias of) { @link ArrayCollection::toArray() }
     *
     * @see ArrayCollection::toArray()
     * @return array The PHP array representation of this collection.
     */
    public function getElements()
    {
        return $this->toArray();
    }
}
