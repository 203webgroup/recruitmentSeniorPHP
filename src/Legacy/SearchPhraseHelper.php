<?php

namespace Legacy;

interface SearchPhraseHelper
{
    public function cleanPhrase($phase);
    public function extract($cleanPhrase, $wtf);
    public function cleanKeywords($keywords);
    public function insertion();
}
