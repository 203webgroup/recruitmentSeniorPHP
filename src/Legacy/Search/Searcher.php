<?php

namespace Legacy\Search;

interface Searcher
{
    public function search($searchParams, $options);
    public function hasResults();
    public function getPaginatorAsArray();
    public function getOrderBy();
    public function getFoundCount();
    public function getResults();
    public function isKeyword($keyword);
}
