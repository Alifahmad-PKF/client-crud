<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Models\Client;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ClientsExport;
use Illuminate\Support\Facades\Schema;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::all(); // Mengambil semua data client
        $columns = Schema::getColumnListing('clients'); // Mengambil semua kolom dari tabel clients
        // Jumlah data yang ingin ditampilkan per halaman
        $perPage = 10;

        // Ambil data dari database dan paginate
        $clients = Client::paginate($perPage);

        // Kirim data ke view, termasuk jumlah total dan halaman yang sedang aktif
        return view('clients.index', [
            'clients' => $clients,
            'total' => $clients->count(), // Total data
            'from' => $clients->firstItem(), // Data pertama di halaman
            'to' => $clients->lastItem(), // Data terakhir di halaman
            'columns' => $columns, // Mengirimkan kolom ke view
        ]);
    }

    public function filter(Request $request)
{
    $columns = $request->column;
    $operators = $request->operator;
    $values = $request->value;

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

        $query->where($column, $operator, $value); // Default: AND
    }

    $clients = $query->paginate(10);
    $columns = Schema::getColumnListing('clients');

    return view('clients.index', [
        'clients' => $clients,
        'total' => $clients->total(),
        'from' => $clients->firstItem(),
        'to' => $clients->lastItem(),
        'columns' => $columns,
    ]);
}



    public function export(Request $request)
    {
        return Excel::download(new ClientsExport($request->filter, $request->search), 'clients.xlsx');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'address' => 'required',
            'pic' => 'required',
            'mobile_phone' => 'required',
            'gmail' => 'required',
        ]);

        // Generate code_client automatically
        $lastClient = Client::latest()->first();
        $nextCode = $lastClient ? 'PKF.' . str_pad(substr($lastClient->code_client, -4) + 1, 4, '0', STR_PAD_LEFT) : 'PKF.0001';

        Client::create([
            'code_client' => $nextCode,
            'name' => $request->name,
            'address' => $request->address,
            'pic' => $request->pic,
            'mobile_phone' => $request->mobile_phone,
            'gmail' => $request->gmail,
        ]);

        return back()->with('success', 'Data Berhasil Disimpan');
    }

    public function search(Request $request)
    {
        $query = $request->get('search');

        // Gunakan paginate() untuk menjaga pagination pada hasil pencarian
        $clients = Client::where('name', 'like', '%' . $query . '%')->paginate(10);

        // Kirim data yang dipaginasikan ke view, termasuk total data dan halaman yang sedang aktif
        return view('clients.index', [
            'clients' => $clients,
            'total' => $clients->total(), // Total data setelah filter
            'from' => $clients->firstItem(), // Data pertama di halaman
            'to' => $clients->lastItem(), // Data terakhir di halaman
            'columns' => Schema::getColumnListing('clients'), // Kirimkan kolom ke view
        ]);
    }

    public function edit($id)
    {
        $client = Client::find($id);
        return view('clients.edit', compact('client'));
    }

    public function update(Request $request, $id)
    {
        $client = Client::find($id);
        $client->update($request->all());
        return redirect()->route('clients.index')->with('success', 'Data Berhasil Diperbarui');
    }

    public function destroy($id)
    {
        $client = Client::find($id);
        $client->delete();
        return back()->with('success', 'Data Berhasil Dihapus');
    }
}
