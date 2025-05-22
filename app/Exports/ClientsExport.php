<?php

namespace App\Exports;

use App\Models\Client;
use Maatwebsite\Excel\Concerns\FromCollection;

class ClientsExport implements FromCollection
{
    protected $filter;
    protected $search;

    public function __construct($filter, $search)
    {
        $this->filter = $filter;
        $this->search = $search;
    }

    public function collection()
    {
        $query = Client::query();

        if ($this->filter) {
            $query->where('column_name', $this->filter);  // Sesuaikan dengan kolom filter
        }

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');  // Pencarian
        }

        return $query->get();  // Ambil data yang sudah difilter
    }
}
