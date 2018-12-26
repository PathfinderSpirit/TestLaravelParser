<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{    
    protected $primaryKey='id';
    protected $table='posts';   
    public $timestamps = false;
    protected $fillable=array('text','articleId','created_at');

}
