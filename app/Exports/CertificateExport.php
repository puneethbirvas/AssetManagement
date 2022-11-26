<?php

namespace App\Exports;

use App\Models\Certificate;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CertificateExport implements FromCollection, WithHeadings
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
            'VendorName', 
            'PeriodFrom',
            'PeriodTo',
            'InspectionPattern',
            'Department',
            'Section',
            'AssetType',
            'AssetName',
        ];
    }

}
