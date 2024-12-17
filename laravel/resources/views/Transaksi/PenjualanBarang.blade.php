@extends('index')

@section('content')
<style>
    .custom-table-container {
        margin: 20px;
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 10px;
        background-color: #f9f9f9;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    .custom-table {
        width: 100%;
        border-collapse: collapse;
        font-family: Arial, sans-serif;
        font-size: 14px;
        color: #333;
    }
    .custom-table thead tr {
        background-color: #4CAF50;
        color: #fff;
        text-align: left;
    }
    .custom-table thead th {
        padding: 12px 15px;
        border-bottom: 2px solid #ddd;
        color: #000; 
    }
    .custom-table tbody tr {
        border-bottom: 1px solid #ddd;
        transition: background-color 0.2s ease;
    }
    .custom-table tbody tr:nth-child(odd) {
        background-color: #f2f2f2;
    }
    .custom-table tbody tr:hover {
        background-color: #dff0d8;
    }
    .custom-table tbody td {
        padding: 12px 15px;
        text-align: left;
    }
    .custom-table tbody td:first-child,
    .custom-table thead th:first-child {
        font-weight: bold;
    }
@foreach( DB::table('view_detail_pengadaan')->get() as $item)
    .custom-table .hover-id-{{ $item->iddetail_pengadaan }} {
        position: relative;
    }

    .custom-table .hover-id-{{ $item->iddetail_pengadaan }}:hover .tooltip-text-{{ $item->iddetail_pengadaan }} {
        visibility: visible;
        opacity: 1;
        transform: translateX(10px); 
    }

    .tooltip-text-{{ $item->iddetail_pengadaan }} {
        visibility: hidden;
        position: absolute;
        left: 10%;
        top: 20%;
        background-color: #4CAF50;
        color: white;
        padding: 5px 10px;
        border-radius: 5px;
        font-size: 12px;
        transition: visibility 0.3s, opacity 0.3s, transform 0.3s;
        opacity: 0;
        white-space: nowrap;
    }
@endforeach
</style>
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
        <div class="card-body">
        <div class="d-flex justify-content-between align-items-center ">
            <h4 class="card-title mb-0">Table Penjualan</h4>
            <div class="d-flex align-items-center mt-2 mt-md-0">
                
                <button type="button" class="btn btn-info btn-rounded btn-sm me-2" data-toggle="modal" data-target=".bd-example-modal-lg">
                    + Tambah
                </button>
            
                <a class="ml-3" href="{{ route('detail-penjualan.index') }}">
                    <button type="button" class="btn btn-dark btn-rounded btn-sm">
                        Detail Penjualan
                    </button>
                </a>
            </div>
        </div>
            <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                <tr>
                        <th>ID Penjualan</th>
                        <th>ID User</th>
                        <th>ID Margin Penjualan</th>
                        <th>Sub Total Nilai</th>
                        <th>PPN</th>
                        <th>Total Nilai</th>
                        <th>Created At</th>
                        <th></th>
                    
                </tr>
                </thead>
                <tbody>
                    @foreach ( $TablePenjualan as $value )
                         <tr>
                            <td>
                             {{ $value->idpenjualan }}
                            </td>
                            <td>
                             {{ $value->iduser }}
                            </td>
                            <td>
                             {{ $value->idmargin_penjualan }}
                            </td>
                            <td>
                             {{ $value->subtotal_nilai }}
                            </td>
                            <td>
                             {{ $value->ppn }}
                            </td>
                            <td>
                             {{ $value->total_nilai }}
                            </td>
                            <td>
                             {{ $value->created_at }}
                            </td>
                            <td class="d-flex">

                            <button data-target=".bd-example-modal-lg-detail-{{ $value->idpenjualan }}" data-toggle="modal" type="button" class="btn btn-secondary btn-sm">Detail</button>
                                <div class="modal fade bd-example-modal-lg-detail-{{ $value->idpenjualan }}" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="col-md-12 grid-margin stretch-card">
                                                <div class="card">
                                                    <div class="card-body">
                                                    <h4 class="card-title">Detail Penjualan</h4>
                                                        <div class="table-responsive mt-3">                                                        
                                                            <div class="custom-table-container">
                                                                <table class="custom-table">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>ID Detail Penjualan</th>
                                                                            <th>ID Barang</th>
                                                                            <th>Harga Satuan</th>
                                                                            <th>Jumlah</th>
                                                                            <th>Sub Total</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                    
                                                                            @foreach(DB::table('view_detail_penjualan')->where('penjualan_idpenjualan', $value->idpenjualan)->get() as $item)
                                                                                <tr>
                                                                                    <td>{{ $item->iddetail_penjualan }}</td>
                                                                                    <td class="hover-id-{{ $item->iddetail_penjualan }}" data-name="Nama Barang 1">
                                                                                        <span class="tooltip-text">{{ $item->idbarang }}</span>
                                                                                    </td>
                                                                                    <td>{{ $item->harga_satuan }}</td>
                                                                                    <td>{{ $item->jumlah }}</td>
                                                                                    <td>{{ $item->subtotal }}</td>
                                                                                </tr>
                                                                            @endforeach
                                                    
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                            <form action="{{ route('penjualan.destroy', $value->idpenjualan) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus Penjualan ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm ml-3">Hapus</button>
                            </form>
                            <button data-target=".bd-example-modal-lg-{{ $value->idpenjualan }}" data-toggle="modal" type="button" class="btn btn-success btn-sm ml-3">Edit</button>
                                <div class="modal fade bd-example-modal-lg-{{ $value->idpenjualan }}" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="col-md-12 grid-margin stretch-card">
                                                <div class="card">
                                                    <div class="card-body">
                                                    <h4 class="card-title">Edit Penjualan</h4>
                                                    <form class="forms-sample" action="{{ route('penjualan.update', $value->idpenjualan) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                       
                                                        <div class="form-group">
                                                            <label for="exampleSelectGender">ID User</label>
                                                            <select name="iduser" class="form-control" id="exampleSelectGender">

                                                                    @foreach ( DB::table('view_user')->get() as $values)
                                                                        @if ( $values->id == $value->iduser )
                                                                        <option value="{{ $values->id }}">{{ $values->id }} - {{ $values->name }}</option>
                                                                        @endif
                                                                    @endforeach
                                                                    @foreach ( DB::table('view_user')->get() as $values)
                                                                        @if ( $values->id !== $value->iduser )
                                                                        <option value="{{ $values->id }}">{{ $values->id }} - {{ $values->name }}</option>
                                                                        @endif
                                                                    @endforeach

                                                            </select>
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="exampleSelectGender">ID Margin Penjualan</label>
                                                            <select name="idvendor" class="form-control" id="exampleSelectGender">
                                                                <option value=""></option>

                                                                @foreach ( DB::table('view_margin_penjualan')->get() as $values)                             
                                                                    <option value="{{ $values->idmargin_penjualan }}">{{ $values->idmargin_penjualan }} - ID User ( {{ $values->iduser }} ) - Persen ( {{ $values->persen }} ) - Status ( {{ $values->status }} )</option>                              
                                                                @endforeach

                                                            </select>
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="exampleInputUsername1">Sub Total Nilai</label>
                                                            <input type="number" class="form-control" name="sub_total_nilai" Required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="exampleInputUsername1">PPN</label>
                                                            <input type="number" class="form-control" name="ppn" Required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="exampleInputUsername1">Total Nilai</label>
                                                            <input type="number" class="form-control" name="total_nilai" Required>
                                                        </div>

                                                        <button type="submit" class="btn btn-primary mr-2">Submit</button>
                                                        <button type="button" data-dismiss="modal" class="btn btn-light">Cancel</button>
                                                    </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
        </div>
        </div>
    </div>

    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Tambah Penjualan</h4>
                  <form class="forms-sample" action="{{ route('penjualan.store') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label for="exampleSelectGender">ID User</label>
                        <select name="iduser" class="form-control" id="exampleSelectGender">
                            <option value=""></option>
                            
                            @foreach ( DB::table('view_user')->get() as $values)                             
                                <option value="{{ $values->id }}">{{ $values->id }} - {{ $values->name }}</option>
                            @endforeach

                        </select>
                    </div>

                    <div class="form-group">
                        <label for="exampleSelectGender">ID Margin Penjualan</label>
                        <select name="idmargin" class="form-control" id="exampleSelectGender">
                            <option value=""></option>

                            @foreach ( DB::table('view_margin_penjualan')->get() as $values)                             
                                <option value="{{ $values->idmargin_penjualan }}">{{ $values->idmargin_penjualan }} - ID User ( {{ $values->iduser }} ) - Persen ( {{ $values->persen }} ) - Status ( {{ $values->status }} )</option>                              
                            @endforeach

                        </select>
                    </div>

                    <div id="dynamicFields">
                        <div class="form-row dynamic-item">
                            <div class="form-group col-md-6">
                                <label for="idBarang">ID Barang</label>
                                <select name="idbarang[]" class="form-control idBarang" id="idBarang">
                                    <option value="">Pilih Barang</option>
                                </select>
                            </div>

                        <div class="form-group col-md-6">
                            <label for="jumlah">Jumlah Penjualan</label>
                            <input type="number" class="form-control jumlah" name="jumlah[]">
                            <div class="d-flex mt-3 justify-content-end">
                                <button type="button" class="btn btn-danger mr-2 remove-item"> - </button>
                                <button type="button" class="btn btn-info add-item"> + </button>
                            </div>
                        </div>
                        </div>
                    </div>


                    <button type="submit" class="btn btn-primary mr-2">Submit</button>
                    <button type="button" data-dismiss="modal" class="btn btn-light">Cancel</button>
                  </form>
                </div>
              </div>
            </div>
            </div>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
    $(document).ready(function () {
        // Load barang saat halaman dimuat
        loadBarangOptions();

        function loadBarangOptions() {
            $.ajax({
                url: '/get-barang-penerimaan',
                type: 'GET',
                success: function (data) {
                    let options = '<option value="">Pilih Barang</option>';
                    $.each(data, function (index, item) {
                        options += `<option value="${item.idbarang}" data-max="${item.total_diterima}">
                                        ${item.idbarang} - ${item.nama}
                                    </option>`;
                    });
                    $('#dynamicFields .idBarang').html(options);
                },
                error: function (xhr, status, error) {
                    console.error('AJAX Error: ', xhr.responseText);
                    alert('Terjadi kesalahan saat memuat data barang.');
                }
            });
        }

        $('#dynamicFields').on('click', '.add-item', function () {
            const newItem = $('.dynamic-item:first').clone();
            newItem.find('input, select').val(''); 
            newItem.find('.jumlah').attr('placeholder', 'Masukkan jumlah penerimaan').removeAttr('max'); 
            $('#dynamicFields').append(newItem);
        });

      
        $('#dynamicFields').on('click', '.remove-item', function () {
            if ($('.dynamic-item').length > 1) {
                $(this).closest('.dynamic-item').remove();
            } else {
                alert('Tidak dapat menghapus semua item!');
            }
        });

        $(document).on('change', '.idBarang', function() {
        var idbarang = $(this).val();
        var jumlahInput = $(this).closest('.dynamic-item').find('input[name="jumlah[]"]'); 

        if (idbarang) {
            $.ajax({
                url: '/get-max-jumlah/' + idbarang, 
                type: 'GET',
                success: function(response) {
                    jumlahInput.attr('max', response.max);
                    jumlahInput.attr('placeholder', 'Jumlah barang yang ada pada kartu stok : ' + response.max);
                },
                error: function() {
                    alert('Terjadi kesalahan saat mengambil data.');
                }
            });
        }
    });
    });
</script>
@endsection