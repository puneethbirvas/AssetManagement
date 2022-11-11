<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UntagAsset extends Model
{
    use HasFactory;
    protected $fillable = [
        "department",
        "section",
        "assetType",
        "assetName",
        "resonForUntag",
        "tag",
    ];
}
