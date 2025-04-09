
<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::all();  // Mengambil semua data client
        return view('clients.index', compact('clients'));  // Mengirim data clients ke view
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
        $clients = Client::where('name', 'like', '%' . $query . '%')->get();
        return view('clients.index', compact('clients'));
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
