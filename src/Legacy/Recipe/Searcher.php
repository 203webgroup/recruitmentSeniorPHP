<?php

namespace Legacy\Recipe;

use Legacy\DB\Adapter;

class Searcher implements \Legacy\Search\Searcher
{
    const FIRST_PAGE = 1;

    const ORDER_BY_ORIGINAL_TITLE = 'a';
    const ORDER_BY_NUM_SAVED = 'b';
    const ORDER_BY_DATE = 'd';

    private $page;
    private $orderBySql;
    private $siteId;
    private $keyword;

    private $adapter;
    private $results;

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }

    public function search($siteId, $keyword = '', $page = 1, $orderBy = null)
    {
        $this->initializeQuery($siteId, $keyword, $page, $orderBy);
        $this->doSearch();

        return $this;
    }

    private function initializeQuery($siteId, $keyword, $page, $orderBy)
    {
        $this->page = $page;
        $this->loadSearchParams($siteId, $keyword)
            ->mountOrderBy($orderBy);
    }

    protected function doSearch()
    {
        list($query, $bind) = $this->getQueryAndBind();
        $this->results = $this->adapter->query($query, $bind);
        $this->setMaxRows();

        return $this;
    }

    private function getQueryAndBind()
    {
        list($where, $bind) = $this->getWhereAndBind();
        $sql = sprintf(
            'SELECT * FROM recipes WHERE %s ORDER BY %s',
            $where,
            $this->orderBySql
        );

        return [$sql, $bind];
    }

    private function setMaxRows()
    {
        list($sql, $bind) = $this->getCountQuery();
        $count = $this->adapter->query($sql, $bind);
        $this->maxRows = reset($count);

        return $this;
    }
    private function getCountQuery()
    {
        list($where, $bind) = $this->getWhereAndBind();
        return [
            sprintf('SELECT COUNT(*) FROM recipes WHERE %s', $where),
            $bind
        ];
    }

    private function getWhereAndBind()
    {
        $whereParts = ['1=1'];
        $bind = [];

        if (!is_null($this->siteId)) {
            $whereParts[] = 'site_id = ?';
            $bind[] = $this->siteId;
        }
        if ($this->isKeyword($this->keyword)) {
            $whereParts[] = 'keyword = ?';
            $bind[] = $this->keyword;
        }

        return [implode(' AND ', $whereParts), $bind];
    }

    private function loadSearchParams($siteId, $keyword)
    {
        if ($this->isKeyword($keyword)) {
            $this->keyword = $keyword;
        }

        if (is_numeric($siteId) && $siteId > 0) {
            $this->siteId = $siteId;
        }

        return $this;
    }

    private function mountOrderBy($orderBy)
    {
        $this->orderBy = $orderBy;
        switch ($this->orderBy) {
            case static::ORDER_BY_ORIGINAL_TITLE:
                $this->orderBySql = "original_title ASC";
                break;
            case static::ORDER_BY_NUM_SAVED:
                $this->orderBySql = "num_saved DESC";
                break;
            case static::ORDER_BY_DATE:
                $this->orderBySql = "date DESC";
                break;
            default:
                $this->orderBySql = '1';
        }

        return $this;
    }

    public function isKeyword($keyword)
    {
        return !empty($keyword) && !is_numeric($keyword);
    }

    public function hasResults()
    {
        return !empty($this->results);
    }

    public function getPaginatorAsArray()
    {
        return [
            'total_rows' => $this->getFoundCount(),
            'max_rows' => $this->maxRows
        ];
    }

    public function getOrderBy()
    {
        return $this->orderBy;
    }

    public function getFoundCount()
    {
        return count($this->results);
    }

    public function getResults()
    {
        return $this->results;
    }
}
