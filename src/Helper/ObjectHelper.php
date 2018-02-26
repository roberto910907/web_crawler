<?php
/**
 * @author  Roberto Rielo <roberto910907@gmail.com>.
 *
 * @version Crawler v1.0 26/02/18 12:31 AM
 */

namespace App\Helper;

use App\Model\FilterModel;

abstract class ObjectHelper
{
    /**
     * Creating FilterModel object model from $_POST data.
     *
     * Using Fluent Interface Design Pattern to build the object
     *
     * @param $_POST_DATA
     *
     * @return FilterModel
     */
    public static function createObjectFromPOST($_POST_DATA)
    {
        return (new FilterModel())
            ->setOperator($_POST_DATA['operator'])
            ->setWords($_POST_DATA['words'])
            ->setOrderBy($_POST_DATA['order']);
    }
}
