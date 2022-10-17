<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lable extends Model
{
    use HasFactory;
    protected $fillable = [
        'department' ,
        'selectSection' ,
        'assetType' ,
        'selectAssetType' ,
        'selectAsset' ,
        'selectAssetId' ,
        'code' ,
    ];
}
