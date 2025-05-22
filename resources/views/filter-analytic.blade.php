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
    nav.navbar {
        background-color: #f2f2f2;
        padding-top: 0.75rem;
        padding-bottom: 0.75rem;
        box-shadow: none;
    }
    nav.navbar .navbar-brand img {
        height: 48px;
    }
    nav.navbar .nav-link,
    nav.navbar .dropdown-toggle {
        color: #3b44f6;
        font-weight: bold;
        text-transform: uppercase;
        font-size: 14px;
    }
    nav.navbar .nav-link:hover,
    nav.navbar .dropdown-toggle:hover {
        color: #2d36cc;
    }
    nav.navbar .dropdown-menu {
        border: none;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.05);
    }
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
        cursor: pointer;
    }
    .btn-reset-filter:hover {
        background-color: #f0f0f0;
        color: #2d36cc;
    }
    .btn-reset-filter i {
        font-size: 14px;
        margin-left: 6px;
    }
    .input-group .form-control {
        border-right: none;
    }
    .input-group .btn-link {
        border: 1px solid #ced4da;
        border-left: none;
    }
    .removeFilterBtn {
        cursor: pointer;
        font-size: 18px;
        color: #dc3545;
        margin-right: 6px;
        transition: color 0.3s ease;
    }
    .removeFilterBtn:hover {
        color: #a71d2a;
    }
    .filter-label-wrapper {
        display: flex;
        align-items: center;
    }
</style>

<div class="container mt-5">
    <h1 class="font-weight-bold">Filter Data Analytic</h1>

    <!-- Filter Form -->
    <form method="get" action="{{ route('filter.analytic') }}">
        @foreach (request('column', []) as $index => $col)
            <input type="hidden" name="column[]" value="{{ $col }}">
            <input type="hidden" name="operator[]" value="{{ request('operator')[$index] ?? '' }}">
            <input type="hidden" name="value[]" value="{{ request('value')[$index] ?? '' }}">
            <input type="hidden" name="logic[]" value="{{ request('logic')[$index] ?? 'AND' }}">
        @endforeach

        {{-- Status Filter dan Reset (sudah di-comment sesuai kode Anda) --}}

        <!-- Modal Filter -->
        <div class="modal fade" id="addFilterModal" tabindex="-1" role="dialog" aria-labelledby="addFilterModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addFilterModalLabel">Filter Rekap Data Client</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="filterRow">
                            <div class="row filter-item mb-3">
                                <div class="col-md-3">
                                    <div class="filter-label-wrapper">
                                        {{-- <i class="removeFilterBtn fa-solid fa-trash"></i> --}}
                                        <label><strong>FILTER</strong></label>
                                    </div>
                                    <select class="form-control" name="column[]">
                                        <option value="">Pilih jenis filter</option>
                                        @foreach ($columns as $column)
                                            <option value="{{ $column }}">
                                                @if ($column == 'name')
                                                    Nama Client
                                                @else
                                                    {{ ucfirst(str_replace('_', ' ', $column)) }}
                                                @endif
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
                                <div class="col-md-3">
                                    <label><strong>LOGIC</strong></label>
                                    <select class="form-control" name="logic[]">
                                        <option value="AND" selected>AND</option>
                                        <option value="OR">OR</option>
                                    </select>
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
                    <th></th>
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
                        <td></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Scripts -->
<link rel="stylesheet" href="//cdn.datatables.net/2.3.0/css/dataTables.dataTables.min.css" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="//cdn.datatables.net/2.3.0/js/dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

<script>
    const clientsIndexUrl = "{{ route('filter.analytic') }}";
    const columnLabels = @json($columnLabels);

    $(document).ready(function () {
        const table = $('#clientsTable').DataTable({
            dom: `
            <'left-box d-flex align-items-center mt-2'
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

        // Tombol Add Filter
        const filterButton = `
            <button type="button" class="btn btn-reset-filter ml-2" data-toggle="modal" data-target="#addFilterModal">
                <i class="fas fa-filter mr-1"></i> Add Filter
            </button>
        `;

        // Cek apakah ada filter aktif
        const params = new URLSearchParams(window.location.search);
        const columns = params.getAll('column[]');
        const operators = params.getAll('operator[]');
        const values = params.getAll('value[]');
        const logics = params.getAll('logic[]');

        let resetButton = '';
        let statusFilterHTML = '';

        if (columns.length > 0) {
            resetButton = `
                <a href="${clientsIndexUrl}" class="btn btn-reset-filter ml-2">
                    Reset <i class="fas fa-times-circle ml-1"></i>
                </a>
            `;

            columns.forEach((column, index) => {
                const operator = operators[index] || '';
                const value = values[index] || '';
                const logic = logics[index] || '';

                const operatorSymbol = {
                    '!=': '≠',
                    '=': '=',
                    '>': '>',
                    '<': '<',
                    'like': 'contains'
                }[operator] || operator;

                const label = columnLabels[column] || column.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());

                const query = new URLSearchParams(window.location.search);
                ['column[]', 'operator[]', 'value[]', 'logic[]'].forEach(key => {
                    const arr = query.getAll(key);
                    arr.splice(index, 1);
                    query.delete(key);
                    arr.forEach(val => query.append(key, val));
                });

                const queryString = query.toString();
                const url = `${clientsIndexUrl}${queryString ? '?' + queryString : ''}`;

                statusFilterHTML += `
                    <a href="${url}" class="btn btn-reset-filter ml-2 d-flex align-items-center" style="height: 38px;">
                        <span>
                            ${index > 0 ? `<strong>${logic}</strong>&nbsp;` : ''}
                            ${label} ${operatorSymbol} ${value}
                        </span>
                        <i class="fas fa-times-circle ml-2" style="font-size: 14px;"></i>
                    </a>
                `;
            });
        }

        $('.button-group').append(filterButton + resetButton + statusFilterHTML);
    });

    // Fungsi toggleValueInput dimodifikasi supaya kolom nilai selalu muncul
    function toggleValueInput(row) {
        const valueInputGroup = row.find('input[name="value[]"]').closest('.col-md-3');
        valueInputGroup.show();
    }

    $('.filterRow .filter-item').each(function() {
        toggleValueInput($(this));
    });

    $(document).on('change', 'select[name="column[]"], select[name="operator[]"]', function() {
        const currentRow = $(this).closest('.row');
        toggleValueInput(currentRow);
    });

    $('#addFilterRowBtn').on('click', function() {
        const filterContainer = $('.filterRow');
        const newFilterRow = $(`
            <div class="row filterRowItem mb-3">
                <div class="col-md-3">
                    <div class="filter-label-wrapper">
                        <i class="removeFilterBtn fa-solid fa-trash"></i>
                        <label><strong>FILTER</strong></label>
                    </div>
                    <select class="form-control" name="column[]">
                        <option value="">Pilih jenis filter</option>
                        @foreach ($columns as $column)
                            <option value="{{ $column }}">
                                @if ($column == 'name')
                                    Nama Client
                                @else
                                    {{ ucfirst(str_replace('_', ' ', $column)) }}
                                @endif
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
                <div class="col-md-3">
                    <label><strong>LOGIC</strong></label>
                    <select class="form-control" name="logic[]">
                        <option value="AND" selected>AND</option>
                        <option value="OR">OR</option>
                    </select>
                </div>
            </div>
        `);
        filterContainer.append(newFilterRow);
        toggleValueInput(newFilterRow);
    });

    $(document).on('click', '.removeFilterBtn', function() {
        $(this).closest('.row').remove();
    });
</script>
@endsection
