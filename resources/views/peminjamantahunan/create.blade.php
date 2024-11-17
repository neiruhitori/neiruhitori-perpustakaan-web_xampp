<head>
    <!-- Modal -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <!--Select2-->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- Untuk Kode Pinjam CSS -->
    <style>
        .kode_pinjam {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .kode_pinjam label {
            width: 100px;
            /* Sesuaikan dengan kebutuhan */
            margin-right: 10px;
        }

        .kode_pinjam input {
            flex: 1;
        }

        .kode_pinjam button {
            margin-left: 110px;
            /* Sesuaikan dengan lebar label */
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
                        <h1 class="m-0">Buat Tahunan</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/dashboard">Beranda</a></li>
                            <li class="breadcrumb-item active"><a href="/peminjamantahunan">Peminjaman</a></li>
                            <li class="breadcrumb-item active">Buat</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
        <form method="post" enctype="multipart/form-data" id="profile_setup_frm"
            action="{{ route('peminjamantahunan.store') }}">
            @csrf
            <div class="row">
                <div class="col-md-12 border-right">
                    <div class="p-3 py-5">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="text-right">Buat Peminjaman Tahunan</h4>
                        </div>
                        <label>Kode Pinjam :</label>
                        <br>
                        <div class="kode_pinjam">
                            @error('kls')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                            <select id="kls" name="kls" class="form-control col-md-2">
                                <option selected disabled>Pilih Kelas</option>
                                <option>VIIA</option>
                                <option>VIIB</option>
                                <option>VIIC</option>
                                <option>VIID</option>
                                <option>VIIE</option>
                                <option>VIIF</option>
                                <option>VIIG</option>
                                <option>VIIIA</option>
                                <option>VIIIB</option>
                                <option>VIIIC</option>
                                <option>VIIID</option>
                                <option>VIIIE</option>
                                <option>VIIIF</option>
                                <option>VIIIG</option>
                                <option>IXA</option>
                                <option>IXB</option>
                                <option>IXC</option>
                                <option>IXD</option>
                                <option>IXE</option>
                                <option>IXF</option>
                                <option>IXG</option>
                            </select>
                            @error('absen')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                            <input type="text" class="form-control col-md-2" id="absen" name="absen"
                                placeholder="No Absen" autocomplete="off"/>
                            @error('tgl')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                            <input type="date" class="form-control col-md-2" id="tgl" name="tgl" autocomplete="off"/>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <label for="inputStatus">Nama :</label>
                                @error('siswas_id')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                                <select id="peminjamantahunan_name" name="siswas_id" class="form-control">
                                    <option selected disabled>Pilih Siswa</option>
                                    @foreach ($siswa as $sw)
                                        <option value="{{ $sw->id }}">{{ $sw->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            {{-- <div class="col-md-6">
                                <label for="inputStatus">Kelas :</label>
                                @error('kelas')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                                <select id="kelas" name="kelas" class="form-control">
                                    <option selected disabled>Pilih Kelas</option>
                                    <option>VII A</option>
                                    <option>VII B</option>
                                    <option>VII C</option>
                                    <option>VII D</option>
                                    <option>VII E</option>
                                    <option>VII F</option>
                                    <option>VII G</option>
                                    <option>VIII A</option>
                                    <option>VIII B</option>
                                    <option>VIII C</option>
                                    <option>VIII D</option>
                                    <option>VIII E</option>
                                    <option>VIII F</option>
                                    <option>VIII G</option>
                                    <option>IX A</option>
                                    <option>IX B</option>
                                    <option>IX C</option>
                                    <option>IX D</option>
                                    <option>IX E</option>
                                    <option>IX F</option>
                                    <option>IX G</option>
                                </select>
                            </div> --}}
                            <!-- Date and time -->
                            <div class="form-group col-md-2">
                                <label>Tanggal Pinjam :</label>
                                @error('jam_pinjam')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                                <div class="input-group date" id="reservationdatetime" data-target-input="nearest">
                                    <input type="date" name="jam_pinjam" class="form-control datetimepicker-input"
                                        data-target="#reservationdatetime" autocomplete="off"/>
                                    <div class="input-group-append" data-target="#reservationdatetime"
                                        data-toggle="datetimepicker">
                                    </div>
                                </div>
                            </div>
                            <!-- /.form group -->
                            <!-- Date and time -->
                            <div class="form-group col-md-2">
                                <label>Tanggal Kembali :</label>
                                @error('jam_kembali')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                                <div class="input-group date" id="reservationdatetime" data-target-input="nearest">
                                    <input type="date" name="jam_kembali" class="form-control datetimepicker-input"
                                        data-target="#reservationdatetime" autocomplete="off"/>
                                    <div class="input-group-append" data-target="#reservationdatetime"
                                        data-toggle="datetimepicker">
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="input-group mb-3"  id="table">
                                <table class="table table-bordered">
                                    <tr>
                                        <th>Buku</th>
                                        <th>Kode Buku</th>
                                        <th>Jumlah Buku</th>
                                        <th>Action</th>
                                    </tr>
                                    <tr>
                                        <td><input type="text" name="buku[]" id="buku" class="form-control"
                                                placeholder="Masukkan Nama Buku"></td>
                                        <td><input type="text" class="form-control" id="kodebuku" name="kodebuku[]"
                                                placeholder=" Masukkan Kode Buku" /></td>
                                        <td><input type="text" class="form-control" id="jml_buku" name="jml_buku[]"
                                                placeholder=" Masukkan Jumlah Buku yang di Pinjam" /></td>
                                        <td><button class="btn btn-success add_buku" type="button" name="add"
                                                id="add">Add More</button></td>
                                    </tr>
                                </table>
                            </div> --}}
                            <!-- /.form group -->
                            {{-- <div class="col-md-6">
                                <label>Description :</label>
                                @error('description')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                                <textarea type="text" class="form-control" id="description" name="description"
                                    placeholder="Silahkan Masukkan Deskripsi Peminjaman"></textarea>
                            </div> --}}
                            <div class="col-md-6">
                                <!-- Button trigger modal -->
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#exampleModal">
                                    Buat Pinjaman
                                </button>

                                <!-- Modal -->
                                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                                    aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="exampleModalLabel">Konfirmasi Pinjaman</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                Pastikan data pinjaman sudah benar!
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Batal
                                                </button>

                                                <button id="btn" class="btn btn-primary profile-button"
                                                    type="submit">
                                                    Buat
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
        {{-- untuk Multiple Insert --}}
        {{-- <script>
            const add = document.querySelectorAll(".input-group .add_buku")
            add.forEach(function(e) {
                e.addEventListener('click', function() {
                    let element = this.parentElement
                    // console.log(element);
                    let newElement = document.createElement('div')
                    newElement.classList.add('input-group', 'mb-3')
                    newElement.innerHTML = `
                <tr>
                    <td><input type="text" name="buku[]" id="buku" class="form-control" placeholder="Masukkan Nama Buku"></td>
                    <td><input type="text" class="form-control" id="kodebuku" name="kodebuku[]" placeholder=" Masukkan Kode Buku" /></td>
                    <td><input type="text" class="form-control" id="jml_buku" name="jml_buku[]" placeholder=" Masukkan Jumlah Buku yang di Pinjam" /></td>
                    <td><button class="btn btn-danger remove_buku" type="button" name="add" id="add">Remove</button></td>
                </tr>`
                    document.getElementById('table').appendChild(newElement)
                    callEvent()
                })
            })
            callEvent()

            function callEvent() {
                // Dibawah ini adalah untuk Hapus Input yang ditambahkan
                document.querySelector('div').querySelectorAll('.remove_buku').forEach(function(remove) {
                    remove.addEventListener('click', function(elmClick) {
                        elmClick.target.parentElement.remove()
                    })
                })
            }
        </script> --}}
    @endsection
