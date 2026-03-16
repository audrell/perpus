<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SettingApp extends Model
{
    protected $fillable = ['name_app', 'short_cut_app', 'image'];
}
