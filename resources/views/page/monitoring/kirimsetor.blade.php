@extends('layouts.base_admin.base_dashboard')
@section('judul', $title )
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
      <div class="row">
        {{-- <div class="col-sm-6">
          <h1></h1>
        </div> --}}
        <div class="col-sm-6">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Laporan</a></li>
            <li class="breadcrumb-item active">{{ $title }}</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        {{ $title }}
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Tanggal Awal</label>
                                    <div class="input-group date datepicker">
                                        <input type="text" class="form-control" name="tanggal1" id="tanggal1" value="{{ $tanggal1 }}" />
                                        <div class="input-group-append">
                                          <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                        </div>
                                      </div>                                      
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Tanggal Akhir</label>
                                    <div class="input-group date datepicker">
                                        <input type="text" class="form-control" name="tanggal2" id="tanggal2" value="{{ $tanggal2 }}" />
                                        <div class="input-group-append">
                                          <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                        </div>
                                      </div>                                      
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Aksi</label><br>
                                    <button class="btn btn-primary" onclick="filter()">Tampilkan</button>                                
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-md-6">
                                <table class="table table-bordered">
                                    <thead style="text-align: center">
                                        <tr>
                                            <th rowspan="2">No</th>
                                            <th rowspan="2">Jenis</th>
                                            <th colspan="3">Jumlah</th>
                                        </tr>
                                        <tr>
                                            <th>PO</th>
                                            <th>DZ</th>
                                            <th>PCS</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $jumlahrekap=0;
                                            $jumlahdzrekap=0;
                                            $jumlahpcsrekap=0;
                                        @endphp
                                        @foreach ($rekap as $item)
                                            <tr>
                                                <td align="center">{{ $item->idjenis }}</td>
                                                <td>{{ $item->nama }}</td>
                                                <td align="center">{{ $item->jumlah }}</td>
                                                <td align="center">{{ $item->dz }}</td>
                                                <td align="center">{{ $item->pcs }}</td>
                                            </tr>
                                            @php
                                                $jumlahrekap+=($item->jumlah);
                                                $jumlahdzrekap+=($item->dz);
                                                $jumlahpcsrekap+=($item->pcs);
                                            @endphp
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr align="center">
                                            <td colspan="2">Total</td>
                                            <td>{{ $jumlahrekap }}</td>
                                            <td>{{ $jumlahdzrekap }}</td>
                                            <td>{{ $jumlahpcsrekap }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-bordered">
                                    <thead style="text-align: center">
                                        <tr>
                                            <th rowspan="2">No</th>
                                            <th rowspan="2">Kode PO</th>
                                            <th colspan="2">Jumlah</th>
                                        </tr>
                                        <tr>
                                            <th>DZ</th>
                                            <th>PCS</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $nokemeja=1;
                                            $jumlahrekap=0;
                                            $jumlahdzrekap=0;
                                            $jumlahpcsrekap=0;
                                        @endphp
                                        @foreach ($kemeja as $det)
                                        <tr>
                                            <td align="center">{{ $nokemeja++ }}</td>
                                            <td>{{ $det->kode_po }}</td>
                                            <td align="center">{{ number_format($det->pcs/12,2) }}</td>
                                            <td align="center">{{ $det->pcs }}</td>
                                        </tr>
                                            @php
                                                $jumlahdzrekap+=($det->pcs/12);
                                                $jumlahpcsrekap+=($det->pcs);
                                            @endphp
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr align="center">
                                            <td colspan="2">Total</td>
                                            <td>{{ $jumlahdzrekap }}</td>
                                            <td>{{ $jumlahpcsrekap }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-bordered">
                                    <thead style="text-align: center">
                                        <tr>
                                            <th rowspan="2">No</th>
                                            <th rowspan="2">Kode PO</th>
                                            <th colspan="2">Jumlah</th>
                                        </tr>
                                        <tr>
                                            <th>DZ</th>
                                            <th>PCS</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $nokemeja=1;
                                            $jumlahrekap=0;
                                            $jumlahdzrekap=0;
                                            $jumlahpcsrekap=0;
                                        @endphp
                                        @foreach ($kaos as $det)
                                        <tr>
                                            <td align="center">{{ $nokemeja++ }}</td>
                                            <td>{{ $det->kode_po }}</td>
                                            <td align="center">{{ number_format($det->pcs/12,2) }}</td>
                                            <td align="center">{{ $det->pcs }}</td>
                                        </tr>
                                            @php
                                                $jumlahdzrekap+=($det->pcs/12);
                                                $jumlahpcsrekap+=($det->pcs);
                                            @endphp
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr align="center">
                                            <td colspan="2">Total</td>
                                            <td>{{ $jumlahdzrekap }}</td>
                                            <td>{{ $jumlahpcsrekap }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
@section('script_footer')

<!-- Bootstrap 4 Datepicker -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<script>
    $(document).ready(function () {
      $('.datepicker').datepicker({
        format: 'yyyy-mm-dd', // Format tanggal
        autoclose: true,      // Tutup otomatis setelah memilih tanggal
        todayHighlight: true  // Sorot tanggal hari ini
      });
    });


    function filter(){
        var tanggal1 = $("#tanggal1").val();
        var tanggal2 = $("#tanggal2").val();
        var url='?';

        if(tanggal1){
            url +='&tanggal1='+tanggal1;
        }

        if(tanggal2){
            url +='&tanggal2='+tanggal2;
        }

        location = url;
    }
</script>
  
@endsection