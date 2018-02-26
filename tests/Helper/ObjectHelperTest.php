<?php
/**
 * @author  Roberto Rielo <roberto910907@gmail.com>.
 *
 * @version Crawler v1.0 25/02/18 09:30 PM
 */

namespace Test\Helper;

use App\Helper\ObjectHelper;
use PHPUnit\Framework\TestCase;

class ObjectHelperTest extends TestCase
{
    public function testObjectCreationFromPOST()
    {
        $_POST_DATA = ['operator' => '<', 'words' => 5, 'order' => 'comments'];

        $filterModelObject = ObjectHelper::createObjectFromPOST($_POST_DATA);

        $this->assertEquals($_POST_DATA['operator'], $filterModelObject->getOperator());
        $this->assertEquals($_POST_DATA['words'], $filterModelObject->getWords());
        $this->assertEquals($_POST_DATA['order'], $filterModelObject->getOrderBy());
    }
}
