<?php

namespace Legacy;

interface Paginator
{
    public function initialize($options);
    public function createLinks();
}
