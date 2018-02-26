<?php
/**
 * @author  Roberto Rielo <roberto910907@gmail.com>.
 *
 * @version Crawler v1.0 25/02/18 05:42 PM
 */

namespace Test\Helper;

use App\Helper\StringHelper;
use PHPUnit\Framework\TestCase;

class StringHelperTest extends TestCase
{
    /**
     * @dataProvider wordCountProvider
     *
     * @param $string
     * @param $wordAmmount
     */
    public function testCountWords($string, $wordAmmount)
    {
        $this->assertEquals(StringHelper::countWords($string), $wordAmmount);
    }

    public function wordCountProvider()
    {
        return [
            ['Se cuentan 5 palabras ahora', 5],
            ['Programar es uno de los mejores pasatiempos.', 7],
            ['palabra', 1],
            ['Mostrar que hay 12 espacios creados entre cada palabra que se muestra.', 12],
            [null, 0],
            ['', 0],
            [false, 0],
        ];
    }
}
