<?php

namespace Modules\Ecommerce\Utils;

class StringUtil
{
  public static function generateOrderNo($number)
  {
    return '1' . str_pad($number, 6, "0", STR_PAD_LEFT);
  }
}
