<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;
    protected $fillable = [
        'assetId',
        'department',
        'section',
        'assetName',
        'financialAssetId',
        'vendorName',
        'phoneNumber',
        'email',
        'vendorAddress',
        'assetType',
        'manufaturer',
        'assetModel',
        'poNo',
        'invoiceNo',
        'warrantyStartDate',
        'warrantyEndDate',
        'warrantyDocument',
        'uploadDocument',
        'description',
        'assetImage'
    ];
}
