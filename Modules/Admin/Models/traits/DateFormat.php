<?php

namespace Modules\Admin\Models\traits;

use DateTimeInterface;

trait DateFormat
{
    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }
}
