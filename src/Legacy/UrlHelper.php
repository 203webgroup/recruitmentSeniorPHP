<?php

namespace Legacy;

interface UrlHelper
{
    public function deurlize($url);
    public function segmentArray();
    public function sanitize($keyword);
    public function url($url = '');
}
