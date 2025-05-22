<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use Illuminate\Support\Facades\Schema;

class AnalyticController extends Controller
{
    public function filter(Request $request)
    {
        // Pastikan variabel $columns tidak null
        $columns = $request->column ?? [];
        $operators = $request->operator ?? [];
        $values = $request->value ?? [];

        // Validasi jumlah filter
        if (count($columns) != count($operators) || count($columns) != count($values)) {
            return redirect()->back()->withErrors('All filter fields must be complete.');
        }

        $query = Client::query();

        foreach ($columns as $index => $column) {
            $operator = $operators[$index];
            $value = $values[$index];

            if (strtolower($operator) == 'like') {
                $value = '%' . $value . '%';
            }

            $query->where($column, $operator, $value);
        }

        // Pagination
        $clients = $query->paginate(10);

        // Ambil nama kolom dari tabel clients
        $columns = Schema::getColumnListing('clients'); // Pastikan ini mengembalikan array

        return view('filter-analytic', [
            'clients' => $clients,
            'total' => $clients->total(),
            'from' => $clients->firstItem(),
            'to' => $clients->lastItem(),
            'columns' => $columns,
        ]);
    }
}
