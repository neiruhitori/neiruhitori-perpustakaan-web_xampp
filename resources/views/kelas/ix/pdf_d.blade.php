<!DOCTYPE html>
<html>

<head>
    <title>Data Selesai Meminjam Harian</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            /* background-color: #f0f0f0; */
        }

        .table-kop {
            width: 100%;
            border-collapse: collapse;
        }

        .table-ttd {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            /* padding: 10px; */
            border: 1px solid #a3a3a3;
            background-color: #898989;
        }

        .table-isi {
            width: 100%;
            border-collapse: collapse;
            background-color: #f0f0f0;
            border: 1px solid #a3a3a3;
            table-layout: fixed;
            font-size: 12px;
            /* Ukuran huruf diperkecil */
        }

        .table-isi th {
            font-size: 12px;
            /* Ukuran huruf untuk header */
            padding: 6px;
            /* Padding dikurangi sedikit */
        }

        .table-isi td {
            word-wrap: break-word;
            overflow-wrap: break-word;
            white-space: normal;
            border: 1px solid #a3a3a3;
            /* padding: 6px; */
            /* Padding dikurangi sedikit */
        }
    </style>
</head>

<body>
    <table class="table-kop">
        <tr>
            <td width="25" align="center"><img src="AdminLTE-3.2.0/dist/img/smp2.png" width="60%"></td>
            <td width="30" align="center">
                <h3 style="line-height: 0.20em;">PEMERINTAH KABUPATEN LUMAJANG</h3>
                <h3 style="line-height: 0.20em;">DINAS PENDIDIKAN</h3>
                <h2 style="line-height: 0.20em;">SMP NEGERI 02 KLAKAH</h2>
                <b style="line-height: 0.20em;">Jl. Ranu No.23, Linduboyo, Klakah, Kec. Klakah, Kabupaten Lumajang, Jawa Timur 67356</b>
            </td>
            <td width="25" align="center"><img src="AdminLTE-3.2.0/dist/img/lumajang.png" width="60%"></td>
        </tr>
    </table>

    <hr />
    <br />
    <br />

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <table id="example2" class="table-isi" style="width: 100%" cellpadding="8">
                            <thead>

                                <tr>
                                    <th>No</th>
                                    <th>NISN</th>
                                    <th>Nama</th>
                                    <th>Kelas</th>
                                    <th>Buku</th>
                                    <th>Jumlah Buku</th>
                                    <th>Kode Buku</th>
                                </tr>
                            </thead>
                            <tbody>
                                    @forelse ($ixd as $a)
                                        <tr>
                                            <td scope="row" align=center>{{ $loop->iteration }}</td>
                                            <td align="left">{{ optional($a->siswas)->nisn }}</td>
                                            <td align="left">{{ optional($a->siswas)->name }}</td>
                                            <td align="center">{{ optional($a->siswas)->kelas }}</td>
                                            <td>
                                                @foreach ($a->bukus()->get() as $b)
                                                
                                                    <ul type=disc>
                                                        <li>{{ $b->bukucruds->buku }}</li>
                                                    </ul>
                                                
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($a->bukus()->get() as $c)
                                                
                                                    <ul type=circle>
                                                        <li>{{ $c->jml_buku }}</li>
                                                    </ul>
                                                
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($a->bukus()->get() as $d)
                                                
                                                    <ul type=circle>
                                                        <li>{{ $d->kodebuku }}</li>
                                                    </ul>
                                                
                                                @endforeach
                                            </td>
                                        </tr>
                                    @empty
                                    @endforelse
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>

    <br />
    <br />
    <br />

    <table class="table-ttd">
        <tfoot>
            <tr>
                <td width="85" align="center"></td>
                <td width="30" align="center">
                    {{-- <span>Mengetahui</span> --}}
                </td>
                <td width="85" align="center"></td>
            </tr>

            <tr>
                <td width="85" align="center">
                    {{-- <span>Sekertaris,</span> --}}
                    <br />
                    <br />
                    <br />
                    <br />
                    <br />
                    {{-- <span>Nama Sekretaris S.Pd</span> --}}
                </td>

                <td></td>

                <td width="85" align="center">
                    <span>Kepala Perpustakaan</span>
                    <br />
                    <span>SMPN 02 Klakah</span>
                    <br />
                    <br />
                    <br />
                    <br />
                    <br />
                    <span>{{ auth()->user()->name }}</span>
                    <br />
                    <span>NIP. {{ auth()->user()->nip }}</span>

                </td>
            </tr>
        </tfoot>
    </table>

</body>

</html>
