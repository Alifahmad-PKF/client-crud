@extends('master')
@section('content')
    @php
        $from = $from ?? 1;
        $to = $to ?? count($clients ?? []);
        $total = $total ?? count($clients ?? []);
        $columns = $columns ?? ['code_client', 'name', 'address', 'pic', 'mobile_phone', 'gmail'];

        $columnLabels = [
            'code_client' => 'Code Client',
            'name' => 'Nama Client',
            'address' => 'Alamat',
            'pic' => 'PIC',
            'mobile_phone' => 'Mobile Phone',
            'gmail' => 'Gmail',
        ];

        $hasFilter = request()->has('column') && request()->has('operator') && request()->has('value');
    @endphp

    <style>
        /* Styling as per your original code */
        .btn-reset-filter {
            border: 1px solid #ddd;
            color: #3b44f6;
            background-color: #fff;
            padding: 6px 16px;
            border-radius: 6px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            text-decoration: none;
        }

        .btn-reset-filter:hover {
            background-color: #f0f0f0;
            color: #2d36cc;
        }

        .btn-reset-filter i {
            font-size: 14px;
        }

        /* Additional styles for Status Filter and Add Filter Button */
        .filter-label-wrapper {
            display: flex;
            align-items: center;
        }
    </style>

    <div class="container mt-5">
        <h1 class="font-weight-bold">Data Client</h1>
        <button class="btn btn-primary my-3" data-toggle="modal" data-target="#clientModal">Tambah Data Client</button>

        <!-- Filter Form -->
        <form method="get" action="{{ route('filter') }}">
            <div class="d-flex align-items-center mb-3 flex-wrap ap-2">
                @if ($hasFilter)
                    <a href="{{ route('clients.index') }}" class="btn btn-reset-filter ml-2">
                        Reset <i class="fas fa-times-circle ml-1"></i>
                    </a>
                @endif

                @if ($hasFilter)
                    @foreach (request('column') as $index => $column)
                        @php
                            $operator = request('operator')[$index] ?? '';
                            $value = request('value')[$index] ?? '';
                            $columnLabel = $columnLabels[$column] ?? ucfirst(str_replace('_', ' ', $column));
                            $operatorSymbol = match ($operator) {
                                '!=' => '≠',
                                '=' => '=',
                                '>' => '>',
                                '<' => '<',
                                'like' => 'contains',
                                default => $operator,
                            };
                            $query = collect(request()->all());
                            $query->put('column', collect($query->get('column'))->forget($index)->values()->all());
                            $query->put('operator', collect($query->get('operator'))->forget($index)->values()->all());
                            $query->put('value', collect($query->get('value'))->forget($index)->values()->all());
                        @endphp

                        <a href="{{ route('clients.index', $query->toArray()) }}"
                            class="btn btn-reset-filter ml-2 d-flex align-items-center" style="height: 38px;">
                            <span>
                                {{ $columnLabel }} {{ $operatorSymbol }} {{ $value }}
                            </span>
                            <i class="fas fa-times-circle ml-2" style="font-size: 14px;"></i>
                        </a>
                    @endforeach
                @endif

            </div>

            <!-- Modal Filter -->
            <div class="modal fade" id="addFilterModal" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Filter Rekap Data Client</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span>&times;</span></button>
                        </div>
                        <div class="modal-body">
                            <div class="filterRow">
                                <div class="row filter-item mb-3">
                                    <div class="col-md-3">
                                        <label><strong>FILTER</strong></label>
                                        <select class="form-control" name="column[]">
                                            <option value="">Pilih jenis filter</option>
                                            @foreach ($columns as $column)
                                                <option value="{{ $column }}">
                                                    @if ($column == 'name') Nama Client @else {{ ucfirst(str_replace('_', ' ', $column)) }} @endif
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label><strong>OPERATOR</strong></label>
                                        <select class="form-control" name="operator[]">
                                            <option value="">Pilih Operator</option>
                                            <option value="=">Is</option>
                                            <option value="!=">Is Not</option>
                                            <option value=">">Greater Than</option>
                                            <option value="<">Less Than</option>
                                            <option value="like">Contains</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label><strong>NILAI</strong></label>
                                        <input type="text" class="form-control" name="value[]" placeholder="Nilai">
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-outline-success mt-2" id="addFilterRowBtn">
                                <i class="fas fa-plus-circle"></i> Tambah Filter
                            </button>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Apply</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <!-- Table -->
        <div style="overflow-x:auto;">
            <table id="clientsTable" class="table table-bordered display nowrap" style="width:100%">
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
                                <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-warning btn-sm"
                                    title="Edit">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                                <form action="{{ route('clients.destroy', $client->id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Delete"
                                        onclick="return confirm('Are you sure want to delete this client?')">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Tambah Client -->
    <div class="modal fade" id="clientModal" tabindex="-1" role="dialog" aria-labelledby="clientModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ route('clients.store') }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="clientModalLabel">Tambah Data Client</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="code_client">Code Client</label>
                        <input type="text" class="form-control" name="code_client" required>
                    </div>
                    <div class="form-group">
                        <label for="name">Nama Client</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="address">Alamat</label>
                        <input type="text" class="form-control" name="address" required>
                    </div>
                    <div class="form-group">
                        <label for="pic">PIC</label>
                        <input type="text" class="form-control" name="pic" required>
                    </div>
                    <div class="form-group">
                        <label for="mobile_phone">Mobile Phone</label>
                        <input type="text" class="form-control" name="mobile_phone" required>
                    </div>
                    <div class="form-group">
                        <label for="gmail">Gmail</label>
                        <input type="email" class="form-control" name="gmail" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="//cdn.datatables.net/2.3.0/js/dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

    <script>
        $(document).ready(function () {
            const table = $('#clientsTable').DataTable({
                dom: `<'left-box d-flex align-items-center mt-2'
                    <'search-box'f>><"top d-flex mt-2 gap-3 mb-2"<'info-box ms-2'i><'button-group d-flex'B>>rt`,
                scrollX: true,
                autoWidth: true,
                responsive: true,
                paging: false,
                lengthChange: true,
                searching: true,
                ordering: true,
                info: true,
                buttons: [
                    {
                        extend: 'excel',
                        text: '<i class="fas fa-file-export mr-1"></i> Export',
                        className: 'btn btn-reset-filter ml-3'
                    }
                ],
                language: {
                    paginate: {
                        previous: "Prev",
                        next: "Next"
                    }
                }
            });

            // Filter Button & Status Filter code
            const filterButton = `
                <button type="button" class="btn btn-reset-filter ml-2" data-toggle="modal" data-target="#addFilterModal">
                    <i class="fas fa-filter mr-1"></i> Add Filter
                </button>
            `;
            const params = new URLSearchParams(window.location.search);
            const columns = params.getAll('column[]');
            const operators = params.getAll('operator[]');
            const values = params.getAll('value[]');

            let resetButton = '';
            let statusFilterHTML = '';

            if (columns.length > 0) {
                resetButton = `
                    <a href="{{ route('clients.index') }}" class="btn btn-reset-filter ml-2">
                        Reset <i class="fas fa-times-circle ml-1"></i>
                    </a>
                `;
                columns.forEach((column, index) => {
                    const operator = operators[index] || '';
                    const value = values[index] || '';
                    const label = @json($columnLabels)[column] || column.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());

                    statusFilterHTML += `
                        <a href="{{ route('clients.index') }}?column[]=${column}&operator[]=${operator}&value[]=${value}"
                            class="btn btn-reset-filter ml-2 d-flex align-items-center" style="height: 38px;">
                            <span>
                                ${label} ${operator} ${value}
                            </span>
                            <i class="fas fa-times-circle ml-2" style="font-size: 14px;"></i>
                        </a>
                    `;
                });
            }

            $('.button-group').append(filterButton + resetButton + statusFilterHTML);
        });
    </script>
@endsection
