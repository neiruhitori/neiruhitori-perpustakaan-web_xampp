@extends('layouts.app')

@section('title', 'Profile')

@section('contents')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Detail Tahunan</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/dashboard">Beranda</a></li>
                            <li class="breadcrumb-item active"><a href="/peminjamantahunan">Peminjaman</a></li>
                            <li class="breadcrumb-item active">Detail</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
        <form method="post" enctype="multipart/form-data" id="profile_setup_frm" action="#">
            @csrf
            <div class="row">
                <div class="col-md-12 border-right">
                    <div class="p-3 py-5">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="text-right">Detail Peminjaman Tahunan</h4>
                        </div>
                        <div class="row" id="res"></div>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <label>Nama :</label>
                                <input type="text" class="form-control" value="{{ $peminjamantahunan->siswas->name }}"
                                    disabled />
                            </div>

                            <div class="col-md-6">
                                <label>Kelas :</label>
                                <input type="text" class="form-control" value="{{ $peminjamantahunan->siswas->kelas }}"
                                    disabled />
                            </div>
                            <div class="col-md-6">
                                <label>Sampul :</label>
                                <div>
                                    @foreach ($peminjamantahunan->bukus()->get() as $b)
                                        <img src="{{ asset('gambarbukutahunan/' . $b->bukucruds->foto) }}" alt=""
                                            style="width:50px;" class="form-control">
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label>Judul Buku :</label>
                                @foreach ($peminjamantahunan->bukus()->get() as $b)
                                    <input type="text" class="form-control" id="buku" name="buku"
                                        value="{{ $b->bukucruds->buku }}" disabled />
                                @endforeach
                            </div>
                            <div class="col-md-6">
                                <label>Penulis :</label>
                                @foreach ($peminjamantahunan->bukus()->get() as $b)
                                    <input type="text" class="form-control" id="buku" name="buku"
                                        value="{{ $b->bukucruds->penulis }}" disabled />
                                @endforeach
                            </div>
                            <div class="col-md-6">
                                <label>Penerbit :</label>
                                @foreach ($peminjamantahunan->bukus()->get() as $b)
                                    <input type="text" class="form-control" id="buku" name="buku"
                                        value="{{ $b->bukucruds->penerbit }}" disabled />
                                @endforeach
                            </div>
                            <div class="col-md-6">
                                <label>Deskripsi :</label>
                                @foreach ($peminjamantahunan->bukus()->get() as $b)
                                    <input type="text" class="form-control" id="buku" name="buku"
                                        value="{{ $b->bukucruds->description }}" disabled />
                                @endforeach
                            </div>
                            <div class="col-md-6">
                                <label>Kode Buku :</label>
                                @foreach ($peminjamantahunan->bukus()->get() as $d)
                                    <input type="text" class="form-control" id="kodebuku" name="kodebuku"
                                        value="{{ $d->kodebuku }}" disabled />
                                @endforeach
                            </div>
                            <div class="col-md-6">
                                <label>Jumlah Buku :</label>
                                @foreach ($peminjamantahunan->bukus()->get() as $c)
                                    <input type="text" class="form-control" id="jml_buku" name="jml_buku"
                                        value="{{ $c->jml_buku }}" disabled />
                                @endforeach
                            </div>
                            <!-- Date and time -->
                            <div class="form-group">
                                <label>Tanggal Pinjam :</label>
                                <div class="input-group date" id="reservationdatetime" data-target-input="nearest">
                                    <input type="date" name="jam_pinjam" class="form-control datetimepicker-input"
                                        data-target="#reservationdatetime" value="{{ $peminjamantahunan->jam_pinjam }}"
                                        disabled />
                                    <div class="input-group-append" data-target="#reservationdatetime"
                                        data-toggle="datetimepicker">
                                    </div>
                                </div>
                            </div>
                            <!-- /.form group -->
                            <!-- Date and time -->
                            <div class="form-group">
                                <label>Tanggal Kembali :</label>
                                <div class="input-group date" id="reservationdatetime" data-target-input="nearest">
                                    <input type="date" name="jam_kembali" class="form-control datetimepicker-input"
                                        data-target="#reservationdatetime" value="{{ $peminjamantahunan->jam_kembali }}"
                                        disabled />
                                    <div class="input-group-append" data-target="#reservationdatetime"
                                        data-toggle="datetimepicker">
                                    </div>
                                </div>
                            </div>
                            <!-- /.form group -->
                            {{-- <div class="col-md-6">
                                <label>Description :</label>
                                <textarea class="form-control" id="description" name="description"
                                 disabled >{{ $peminjamantahunan->description }}</textarea>
                            </div> --}}
                        </div>
                    </div>

                </div>
            </div>

        </form>
    @endsection
