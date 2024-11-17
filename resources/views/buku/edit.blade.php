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
                        <h1 class="m-0">Edit Buku</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/dashboard">Beranda</a></li>
                            <li class="breadcrumb-item active"><a href="/buku">Buku</a></li>
                            <li class="breadcrumb-item active">Edit</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
        <form method="post" enctype="multipart/form-data" id="profile_setup_frm"
            action="{{ route('buku.update', $buku->id) }}">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-12 border-right">
                    <div class="p-3 py-5">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="text-right">Edit Buku</h4>
                        </div>
                        <div class="row" id="res"></div>
                        <div class="row mt-2">
                            @error('foto')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                            <div class="col-md-6">
                                <label>Sampul :</label>
                                @if ($buku->foto)
                                    <div>
                                        <img src="{{ asset('gambarbukutahunan/' . $buku->foto) }}" alt=""
                                            style="width:100">
                                    </div>
                                @endif
                                <input type="file" class="form-control" name="foto" value=""
                                    autocomplete="off" />
                            </div>
                            <div class="col-md-6">
                                <label>Judul :</label>
                                <input type="text" class="form-control" name="buku" value="{{ $buku->buku }}"
                                    autocomplete="off" />
                            </div>
                            <div class="col-md-6">
                                <label>Penulis :</label>
                                <input type="text" class="form-control" name="penulis" value="{{ $buku->penulis }}"
                                    autocomplete="off" />
                            </div>
                            <div class="col-md-6">
                                <label>Penerbit :</label>
                                <input type="text" class="form-control" name="penerbit" value="{{ $buku->penerbit }}"
                                    autocomplete="off" />
                            </div>
                            <div class="col-md-12 mt-3">
                                <h5>Kode Buku:</h5>
                                @foreach ($buku->kodebukucruds as $stokTahunan)
                                    <div class="form-group">
                                        <label for="kodebuku_{{ $stokTahunan->id }}">Kode Buku
                                            {{ $loop->iteration }}:</label>
                                        <input type="text" class="form-control" id="kodebuku_{{ $stokTahunan->id }}"
                                            name="kodebuku[{{ $stokTahunan->id }}]" value="{{ $stokTahunan->kodebuku }}">
                                    </div>
                                @endforeach
                            </div>
                            <div class="col-md-6">
                                <label>Stok :</label>
                                <input type="number" class="form-control" name="stok" value="{{ $buku->stok }}"
                                    autocomplete="off" />
                            </div>
                            <div class="col-md-6">
                                <label>Deskripsi :</label>
                                <textarea type="text" class="form-control" name="description" autocomplete="off">{{ $buku->description }}</textarea>
                            </div>
                            <div class="col-md-6 mt-2">
                                <div>
                                    <!-- Button trigger modal -->
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#exampleModal">
                                        <i class="fas fa-plus-circle"></i>Ubah
                                    </button>

                                    <!-- Modal -->
                                    <div class="modal fade" id="exampleModal" tabindex="-1"
                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Simpan Perubahan?
                                                    </h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Apakah anda yakin ingin mengubah data?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Batal
                                                    </button>

                                                    <button type="submit" class="btn btn-primary waves-light waves-effect"
                                                        id="update-modal">
                                                        Ubah
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
            </div>

        </form>
    @endsection
