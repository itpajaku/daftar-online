<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sysconf extends Model
{
    protected $connection = "sipp";
    protected  $table = "sys_config";
}
