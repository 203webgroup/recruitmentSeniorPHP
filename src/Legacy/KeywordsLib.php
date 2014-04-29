<?php

namespace Legacy;

interface KeywordsLib
{
    public function generateHash($keyword);
    public function getSearchWords($keyword);
    public function getSearchKeywords($keyword);
}
