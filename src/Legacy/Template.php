<?php

namespace Legacy;

interface Template
{
    public function setTitle($title);
    public function setDescription($description);
    public function setQuery($query);
    public function assign($paramName, $value);
    public function display();
}
