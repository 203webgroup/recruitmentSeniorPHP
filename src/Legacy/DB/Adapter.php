<?php

namespace Legacy\DB;

interface Adapter
{
    public function query($sql, $bind);
}
