<?php
/**
 * @author  Roberto Rielo <roberto910907@gmail.com>.
 * @version Crawler v1.0 26/02/18 12:45 AM
 */
namespace App\Model;

class FilterModel
{
    const ROWS = 'rows';

    private $words;
    private $operator;
    private $orderBy;

    /**
     * @return mixed
     */
    public function getWords()
    {
        return $this->words;
    }

    /**
     * @param mixed $words
     *
     * @return FilterModel
     */
    public function setWords($words)
    {
        $this->words = $words;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getOperator()
    {
        return $this->operator;
    }

    /**
     * @param mixed $operator
     *
     * @return FilterModel
     */
    public function setOperator($operator)
    {
        $this->operator = $operator;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getOrderBy()
    {
        return $this->orderBy;
    }

    /**
     * @param mixed $orderBy
     *
     * @return FilterModel
     */
    public function setOrderBy($orderBy)
    {
        $this->orderBy = $orderBy;

        return $this;
    }
}