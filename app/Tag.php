<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $primaryKey='id';
    protected $table='tags';   
    public $timestamps = false;
    protected $fillable=array('name');
}
