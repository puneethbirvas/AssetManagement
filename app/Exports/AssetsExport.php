<?php

namespace App\Exports;

use App\Models\Asset;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AssetsExport implements FromCollection, WithHeadings
{
    protected $query;
    public function __construct($query){
        $this->query = $query;
      
    } 

    public function collection()
    {
        return  collect($this->query);
    }
    
   
    public function headings() :array
    {
        return [
            'SerialNo', 
            'Department',  
            'Section',
            'MachineName',
            'AssetType',
            'Manufaturer',
            'AssetModel',
            'PODetails',
            'InvoiceDetails',
            'WarrantyStartDate',
            'WarrantyEndDate',
        ];
    }
}
