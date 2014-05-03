<?php

namespace Legacy\Tests;

use Legacy\Recipe\Searcher;

class SearcherTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->adapterMock = $this->getMock('Legacy\\DB\\Adapter');
        $this->sut = new Searcher($this->adapterMock);
    }

    public function tearDown()
    {
        unset($this->adapterMock);
        unset($this->sut);
    }

    /**
     * @dataProvider searchProvider
     */
    public function testSearch(
        $siteId,
        $keyword,
        $page,
        $orderBy = null,
        $where = '1=1',
        $orderBySql = '1',
        $bind = [],
        $recipes = [],
        $maxRows = 0
    ) {
        $findSql = sprintf('SELECT * FROM recipes WHERE %s ORDER BY %s', $where, $orderBySql);
        $countSql = sprintf('SELECT COUNT(*) FROM recipes WHERE %s', $where);

        $this->adapterMock
            ->expects($this->at(0))
            ->method('query')
            ->with($findSql, $bind)
            ->willReturn($recipes);
        $this->adapterMock
            ->expects($this->at(1))
            ->method('query')
            ->with($countSql, $bind)
            ->willReturn([$maxRows]);
        $this->sut->search($siteId, $keyword, $page, $orderBy);

        $this->assertEquals(count($recipes) > 0, $this->sut->hasResults());
        $this->assertEquals($recipes, $this->sut->getResults());

        $paginator = [
            'total_rows' => count($recipes),
            'max_rows' => $maxRows
        ];
        $this->assertEquals($paginator, $this->sut->getPaginatorAsArray());
    }

    public function searchProvider()
    {
        $siteId = rand(1, 100);
        $brownie = [
            'name' => 'brownie',
            'original_name' => 'Chocolat Brownie',
            'num_saved' => 4
        ];

        $sql = 'SELECT * FROM recipes WHERE 1=1 AND site_id = ? AND keyword = ? ORDER BY ';

        return [
            [0, '', $page = 1, $orderBy = null],
            [0, '', $page = 1, $orderBy = 'a', '1=1', 'original_title ASC'],
            [0, '', $page = 1, $orderBy = 'b', '1=1', 'num_saved DESC'],
            [0, '', $page = 1, $orderBy = 'd', '1=1', 'date DESC'],
            [$siteId, 'brownie', $page = 1, '', '1=1 AND site_id = ? AND keyword = ?', '1', [$siteId, 'brownie'], [$brownie], 10]
        ];
    }

    private function assertPrivateEquals($expected, $propertyName, $message = '')
    {
        $property = new \ReflectionProperty($this->sut, $propertyName);
        $property->setAccessible(true);

        $this->assertEquals($expected, $property->getValue($this->sut), $message);
    }
}
