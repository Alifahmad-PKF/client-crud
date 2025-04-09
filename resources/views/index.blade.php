
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Client</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="font-weight-bold">Data Client</h1>
        <button class="btn btn-primary my-3" data-toggle="modal" data-target="#clientModal">Tambah Data Client</button>

        <!-- Search Form -->
        <form action="{{ route('clients.search') }}" method="POST" class="form-inline mb-3">
            @csrf
            <input type="text" name="search" class="form-control" placeholder="Search...">
            <button type="submit" class="btn btn-info ml-2">Search</button>
        </form>

        <!-- Client Data Table -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Code Client</th>
                    <th>Nama Client</th>
                    <th>Alamat</th>
                    <th>PIC</th>
                    <th>Mobile Phone</th>
                    <th>Gmail</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($clients as $client)
                <tr>
                    <td>{{ $client->code_client }}</td>
                    <td>{{ $client->name }}</td>
                    <td>{{ $client->address }}</td>
                    <td>{{ $client->pic }}</td>
                    <td>{{ $client->mobile_phone }}</td>
                    <td>{{ $client->gmail }}</td>
                    <td>
                        <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('clients.destroy', $client->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach

            </tbody>
        </table>
    </div>

    <!-- Modal for Add/Edit Client -->
    <div class="modal fade" id="clientModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tambah Data Client</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('clients.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="name">Nama Client</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="address">Alamat</label>
                            <input type="text" class="form-control" id="address" name="address" required>
                        </div>
                        <div class="form-group">
                            <label for="pic">PIC</label>
                            <input type="text" class="form-control" id="pic" name="pic" required>
                        </div>
                        <div class="form-group">
                            <label for="mobile_phone">Mobile Phone</label>
                            <input type="text" class="form-control" id="mobile_phone" name="mobile_phone" required>
                        </div>
                        <div class="form-group">
                            <label for="gmail">Gmail</label>
                            <input type="email" class="form-control" id="gmail" name="gmail" required>
                        </div>
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
