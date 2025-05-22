<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PKF System</title>

    <!-- Bootstrap 5 & FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <style>
        .logo-bar {
            padding: 10px 20px 5px 20px;
            background-color: white;
            border-bottom: 1px solid #dee2e6;
        }

        .logo-bar img {
            height: 48px;
        }

        .navbar-menu {
            background-color: #eeeeee;
            padding: 4px 0;
            font-size: 13px;
            border-bottom: 1px solid #ddd;
        }

        .navbar-menu .nav-link {
            color: #2a2faf;
            font-weight: 700;
            text-align: center;
            padding: 6px 12px;
            text-transform: uppercase;
        }

        .navbar-menu .dropdown-toggle::after {
            display: inline-block;
            margin-left: 0.25em;
        }

        .navbar-menu .dropdown-menu {
            font-size: 13px;
        }

        body {
            margin: 0;
            padding-top: 0;
        }
    </style>
    <!-- CSS untuk Buttons -->
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css">

</head>

<body>

    <!-- Logo -->
    <div class="logo-bar d-flex align-items-center justify-content-start">
        <a href="{{ url('/') }}" class="navbar-brand mb-0">
            <img src="{{ asset('PKF.png') }}" alt="PKF Logo">
        </a>
    </div>

    <!-- Navbar Menu -->
    <nav class="navbar navbar-expand-lg navbar-menu">
        <div class="container d-flex justify-content-center">
            <ul class="navbar-nav d-flex flex-wrap justify-content-center">
                <li class="nav-item mx-2 text-center">
                    <a class="nav-link" href="{{ url('/') }}">
                        <i class="fas fa-home"></i><br>BERANDA
                    </a>
                </li>

                @php
                    $menus = [
                        ['label' => 'ADM<br>PROYEK'],
                        ['label' => 'FINANCE'],
                        ['label' => 'SIGNATURE'],
                        ['label' => 'SDM'],
                        ['label' => 'SURAT<br>EDARAN'],
                        ['label' => 'PELAPORAN<br>OJK'],
                        ['label' => 'EVENT'],
                        ['label' => 'ASET'],
                        ['label' => 'PETUNJUK'],
                        ['label' => 'PENGATURAN'],
                        ['label' => 'LAPORAN'],
                    ];
                @endphp

                @foreach ($menus as $menu)
                    <li class="nav-item dropdown mx-2 text-center">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            {!! $menu['label'] !!}
                        </a>

                        @if (strip_tags($menu['label']) === 'SDM')
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('clients.index') }}">Data Client</a></li>
                                <li><a class="dropdown-item" href="{{ route('filter.analytic') }}">Filter Data
                                        Analytic</a></li>
                                <li><a class="dropdown-item" href="#">SOON 2</a></li>
                            </ul>
                        @else
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">SOON 1</a></li>
                                <li><a class="dropdown-item" href="#">SOON 2</a></li>
                            </ul>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    </nav>

    <!-- Konten hanya jika bukan halaman beranda -->
    @if (!request()->is('/'))
        <div class="container mt-4">
            @yield('content')
        </div>
    @endif
    @if (request()->is('/'))
        <div class="container mt-4">
            <h2>Dashboard Super Admin</h2>
        </div>
    @endif


    <!-- JS Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- JS untuk Buttons -->
<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
<!-- Dependencies for export buttons -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

</body>

</html>
