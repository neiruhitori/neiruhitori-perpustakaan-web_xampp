<head>
    <!-- Modal -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <!--Select2-->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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
                        <h1 class="m-0">Edit Tahunan</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/dashboard">Beranda</a></li>
                            <li class="breadcrumb-item active"><a href="/peminjamantahunan">Peminjaman</a></li>
                            <li class="breadcrumb-item active">Edit</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
        <form method="post" enctype="multipart/form-data" id="profile_setup_frm"
            action="{{ route('peminjamantahunan.update', $peminjamantahunan->id) }}">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-12 border-right">
                    <div class="p-3 py-5">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="text-right">Edit Peminjaman Tahunan</h4>
                        </div>
                        <div class="row" id="res"></div>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <label>Kode Pinjam :</label>
                                <input type="text" class="form-control" id="kode_pinjam" name="kode_pinjam"
                                    value="{{ $peminjamantahunan->kode_pinjam }}" autocomplete="off" />
                            </div>
                            <!-- Nama dan Kelas -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="siswas_id">Nama:</label>
                                    <select class="form-control" id="peminjamantahunan_name" name="siswas_id">
                                        @foreach ($siswas as $siswa)
                                            <option value="{{ $siswa->id }}"
                                                {{ $peminjamantahunan->siswas_id == $siswa->id ? 'selected' : '' }}>
                                                {{ $siswa->name }} - {{ $siswa->kelas }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <!-- Buku -->
                            {{-- <div class="col-md-6">
                                <div class="form-group">
                                    <label>
                                        <h5>Buku:</h5>
                                    </label>
                                    @foreach ($bukus as $stokTahunan)
                                        <div class="form-group">
                                            <label for="bukucruds_id{{ $stokTahunan->id }}">Buku
                                                {{ $loop->iteration }}:</label>
                                            <input type="text" class="form-control" id="bukucruds_id_{{ $stokTahunan->id }}"
                                                name="bukucruds_id[{{ $stokTahunan->id }}]"
                                                value="{{ $stokTahunan->bukucruds->buku }}" autocomplete="off">
                                        </div>
                                    @endforeach
                                </div>
                            </div> --}}
                            <div class="col-md-6">
                                @foreach ($peminjamantahunan->bukus as $selectedBuku)
                                    <div class="form-group">
                                        <label for="bukucruds_id{{ $selectedBuku->id }}">
                                            <h5>Buku {{ $loop->iteration }} :</h5>
                                        </label>

                                        @error('buku')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror

                                        <select id="bukucrud_id{{ $selectedBuku->id }}" name="bukucruds_id[]"
                                            class="form-control">
                                            @foreach ($bukucrud as $bc)
                                                <option value="{{ $bc->id }}"
                                                    {{-- Tandai yang dipilih sebelumnya --}}
                                                    @if ($bc->id == $selectedBuku->bukucruds_id) selected @endif
                                                    @if ($bc->stok <= 0) disabled @endif>
                                                    {{ $bc->buku }} @if ($bc->stok <= 0)
                                                        (Stok Habis)
                                                    @endif{{-- tampilkan nama buku --}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endforeach
                            </div>
                            <!-- Jumlah Buku -->
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>
                                        <h5>Jumlah Buku:</h5>
                                    </label>
                                    @foreach ($peminjamantahunan->bukus as $p)
                                        <div class="form-group">
                                            <label for="jml_buku_{{ $p->id }}">Jumlah Buku
                                                {{ $loop->iteration }}:</label>
                                            <input type="number" class="form-control" name="jml_buku[{{ $p->id }}]"
                                                value="{{ $p->jml_buku }}" min="1">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <!-- Kode Buku -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>
                                        <h5>Kode Buku:</h5>
                                    </label>
                                    @foreach ($bukus as $stokTahunan)
                                        <div class="form-group">
                                            <label for="kodebuku_{{ $stokTahunan->id }}">Kode Buku
                                                {{ $loop->iteration }}:</label>
                                            <input type="text" class="form-control" id="kodebuku_{{ $stokTahunan->id }}"
                                                name="kodebuku[{{ $stokTahunan->id }}]"
                                                value="{{ $stokTahunan->kodebuku }}" autocomplete="off">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <!-- Date and time -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tanggal Pinjam :</label>
                                    <div class="input-group date" id="reservationdatetime" data-target-input="nearest">
                                        <input type="date" name="jam_pinjam" class="form-control datetimepicker-input"
                                            data-target="#reservationdatetime" value="{{ $peminjamantahunan->jam_pinjam }}"
                                            autocomplete="off" />
                                        <div class="input-group-append" data-target="#reservationdatetime"
                                            data-toggle="datetimepicker">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.form group -->
                            <!-- Date and time -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tanggal Kembali :</label>
                                    <div class="input-group date" id="reservationdatetime" data-target-input="nearest">
                                        <input type="date" name="jam_kembali" class="form-control datetimepicker-input"
                                            data-target="#reservationdatetime"
                                            value="{{ $peminjamantahunan->jam_kembali }}" autocomplete="off" />
                                        <div class="input-group-append" data-target="#reservationdatetime"
                                            data-toggle="datetimepicker">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.form group -->
                            {{-- <div class="col-md-6">
                                <label>Description :</label>
                                <textarea type="text" class="form-control" id="description" name="description">{{ $peminjamantahunan->description }}</textarea>
                            </div> --}}
                            <div class="col-md-6">
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

                                                    <button type="submit"
                                                        class="btn btn-primary waves-light waves-effect"
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
