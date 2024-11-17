<head>
    <!-- Modal -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <!--Select2-->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .message-center-top {
            position: fixed;
            left: 55%;
            transform: translateX(-50%);
            z-index: 100;
            width: 50%;
            /* Sesuaikan lebar pesan */
            text-align: center;
        }
    </style>
</head>


@extends('layouts.app')

@section('title', 'Profile')

@section('contents')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Buat Kode Buku</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/dashboard">Beranda</a></li>
                            <li class="breadcrumb-item active"><a href="/bukuharian">Buku Harian</a></li>
                            <li class="breadcrumb-item active">Buat Buku</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
        <form method="post" enctype="multipart/form-data" id="profile_setup_frm"
            action="{{ route('bukuharian.createkodebukuharianstore') }}">
            @csrf
            <div class="row">
                <div class="col-md-12 border-right">
                    <div class="p-3 py-5">
                        <h4 class="text-left">Buat Kode Buku Harian</h4>
                        <div class="row mt-2">
                            <table class="table table-bordered" id="table">
                                <thead>
                                    <tr>
                                        <th>Judul Buku</th>
                                        <th>Kode Buku</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Baris akan ditambahkan di sini -->
                                    <tr>
                                        <td>
                                            <select id="bukuharian_id" name="bukuharian_id[]"
                                                class="form-control tag-select">
                                                <option selected disabled>Pilih buku</option>
                                                @foreach ($bukuharian as $buk)
                                                    <option value="{{ $buk->id }}"
                                                        @if ($buk->stok <= 0) disabled @endif>
                                                        {{ $buk->buku }} @if ($buk->stok <= 0)
                                                            (Stok Habis)
                                                        @endif
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td><input type="text" class="form-control" name="kodebuku[]"
                                                placeholder="Masukkan Kode Buku" autocomplete="off" /></td>
                                        <td><button class="btn btn-success" type="button" id="add">Tambah</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- Modal Trigger and Button omitted for clarity -->
                        <div class="col-md-6">
                            <!-- Button trigger modal -->
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#exampleModal">
                                Tambah Buku
                            </button>

                            <!-- Modal -->
                            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Buku</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            Pastikan data Buku sudah benar!
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal
                                            </button>

                                            <button id="btn" class="btn btn-primary profile-button" type="submit">
                                                Tambah
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <script>
            // Fungsi untuk menambahkan baris baru
            function addRow() {
                let table = document.getElementById('table').querySelector('tbody');
                let newRow = document.createElement('tr');

                newRow.innerHTML = `
                    <td>
                        <select id="bukuharian_id" name="bukuharian_id[]" class="form-control tag-select">
                            <option selected disabled>Pilih buku</option>
                            @foreach ($bukuharian as $buk)
                                <option value="{{ $buk->id }}" @if ($buk->stok <= 0) disabled @endif>
                                    {{ $buk->buku }} @if ($buk->stok <= 0) (Stok Habis) @endif
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td><input type="text" class="form-control" name="kodebuku[]" placeholder="Masukkan Kode Buku" autocomplete="off" /></td>
                    <td><button class="btn btn-danger remove_buku" type="button">Hapus</button></td>
                `;

                table.appendChild(newRow);
                callEvent(); // Panggil lagi untuk mengaktifkan event pada elemen baru
            }

            // Fungsi untuk menghapus baris
            function callEvent() {
                document.querySelectorAll('.remove_buku').forEach(function(remove) {
                    remove.addEventListener('click', function() {
                        this.closest('tr').remove();
                    });
                });

                // Inisialisasi Select2 pada elemen baru
                $('.tag-select').select2({
                    placeholder: 'Pilih buku',
                });
            }

            // Tambahkan event listener pada tombol 'Tambah'
            document.getElementById('add').addEventListener('click', addRow);

            // Inisialisasi pertama kali
            callEvent();
        </script>

    @endsection
