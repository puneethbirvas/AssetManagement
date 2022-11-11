<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Allocation extends Model
{
    use HasFactory;
    protected $fillable = [
        "department",
        "section",
        "assetType",
        "assetName",
        "userType",
        "empId",
        "empName",
        "userDepartment",
        "user",
        "position",
        "fromDate",
        "toDate"
    ];
}
