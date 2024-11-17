<head>
    <!-- Modal -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
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
                        <h1 class="m-0">Buat Buku</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/dashboard">Beranda</a></li>
                            <li class="breadcrumb-item active"><a href="/bukuharian">Buku Harian</a></li>
                            <li class="breadcrumb-item active">Buat</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
        <form method="post" enctype="multipart/form-data" id="profile_setup_frm" action="{{ route('bukuharian.store') }}">
            @csrf
            <div class="row">
                <div class="col-md-12 border-right">
                    <div class="p-3 py-5">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="text-right">Buat buku</h4>
                        </div>
                        <div class="row" id="res"></div>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <label>Masukkan Sampul :</label>
                                @error('foto')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                                <input type="file" class="form-control" name="foto" autocomplete="off" />
                            </div>
                            <div class="col-md-6">
                                <label>Judul :</label>
                                @error('buku')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                                <input type="text" class="form-control" name="buku"
                                    placeholder=" Masukkan Nama Buku" autocomplete="off"/>
                            </div>
                            <div class="col-md-6">
                                <label>Penulis :</label>
                                @error('penulis')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                                <input type="text" class="form-control" name="penulis" placeholder=" Masukkan Nama Penulis"
                                    autocomplete="off" />
                            </div>
                            <div class="col-md-6">
                                <label>Penerbit :</label>
                                @error('penerbit')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                                <input type="text" class="form-control" name="penerbit" placeholder=" Masukkan Nama Penerbit"
                                    autocomplete="off" />
                            </div>
                            <div class="col-md-6">
                                <label>Stok :</label>
                                @error('stok')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                                <input type="number" class="form-control" name="stok" placeholder=" Masukkan Stok Buku"
                                    autocomplete="off" />
                            </div>
                            <div class="col-md-6">
                                <label>Deskripsi :</label>
                                @error('description')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                                <textarea type="text" class="form-control" name="description"
                                    autocomplete="off"></textarea>
                            </div>
                            <div class="col-md-6 mt-2">
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
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Batal
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
            </div>

        </form>
    @endsection
