<?php

namespace App\Exports;

use App\Models\Allocation;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AllocationExport implements FromCollection, WithHeadings
{
    protected $query;
    public function __construct($query){
        $this->query = $query;
      
    } 

    public function collection()
    {
        return $this->query->get();
    }
    
   
    public function headings() :array
    {
        return [
            'SerialNo', 
            'Department',
            'Section',
            'AssetType',
            'AssetName',
            'AssetId',
            'Assigneduser',
        ];
    }
}
