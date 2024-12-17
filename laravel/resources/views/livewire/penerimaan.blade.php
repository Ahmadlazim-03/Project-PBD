<div class="col-lg-12">
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
            <h4 class="card-title mb-0">Table Penerimaan</h4>
            <div class="d-flex align-items-center mt-2 mt-md-0">
                
                <button type="button" class="btn btn-info btn-rounded btn-sm me-2" data-toggle="modal" data-target=".bd-example-modal-lg">
                    + Tambah
                </button>
            
                <a class="ml-3" href="{{ route('detail-penerimaan.index') }}">
                    <button type="button" class="btn btn-dark btn-rounded btn-sm">
                        Detail Penerimaan
                    </button>
                </a>
            </div>
        </div>
            <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                <tr>
                        <th>ID Penerimaan</th>
                        <th>ID Pengadaan</th>
                        <th>ID User</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th></th>
                </tr>
                </thead>
                <tbody>
                    @foreach ( $TablePenerimaan as $value )
                         <tr>
                            <td>
                             {{ $value->idpenerimaan }}
                            </td>
                            <td>
                             {{ $value->idpengadaan }}
                            </td>
                            <td>
                             {{ $value->iduser }}
                            </td>
                            <td>
                             {{ $value->status }}
                            </td>
                            <td>
                             {{ $value->created_at }}
                            </td>
                            <td class="d-flex">

                                <button data-target=".bd-example-modal-lg-detail-{{ $value->idpenerimaan }}" data-toggle="modal" type="button" class="btn btn-secondary btn-sm">Detail</button>
                                    <div class="modal fade bd-example-modal-lg-detail-{{ $value->idpenerimaan }}" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="col-md-12 grid-margin stretch-card">
                                                    <div class="card">
                                                        <div class="card-body">
                                                        <h4 class="card-title">Detail Penerimaan</h4>
                                                            <div class="table-responsive mt-3">                                                        
                                                                <div class="custom-table-container">
                                                                    <table class="custom-table">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>ID Penerimaan</th>
                                                                                <th>ID Barang</th>
                                                                                <th>Jumlah Terima</th>
                                                                                <th>Harga Satuan Terima</th>
                                                                                <th>Sub Total Terima</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                        
                                                                                @foreach(DB::table('view_detail_penerimaan')->where('idpenerimaan', $value->idpenerimaan)->get() as $item)
                                                                                    <tr>
                                                                                        <td>{{ $item->iddetail_penerimaan }}</td>
                                                                                        <td data-name="Nama Barang 1">
                                                                                            <span class="tooltip-text">{{ $item->idbarang }}</span>
                                                                                        </td>
                                                                                        <td>{{ $item->jumlah_terima }}</td>
                                                                                        <td>{{ $item->harga_satuan_terima }}</td>
                                                                                        <td>{{ $item->sub_total_terima }}</td>
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
                    
                                <form action="{{ route('penerimaan.destroy', $value->idpenerimaan) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus Penerimaan {{ $value->nama }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm ml-3">Hapus</button>
                                </form>
                                <button data-target=".bd-example-modal-lg-{{ $value->idpenerimaan }}" data-toggle="modal" type="button" class="btn btn-success btn-sm ml-3">Edit</button>
                                    <div class="modal fade bd-example-modal-lg-{{ $value->idpenerimaan }}" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="col-md-12 grid-margin stretch-card">
                                                    <div class="card">
                                                        <div class="card-body">
                                                        <h4 class="card-title">Edit Penerimaan</h4>
                                                        <form class="forms-sample" action="{{ route('penerimaan.update', $value->idpenerimaan) }}" method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                        
                                                                <div class="form-group">
                                                                    <label for="exampleSelectGender">ID Pengadaan</label>
                                                                    <select name="idpengadaan" class="form-control" id="exampleSelectGender">
            
                                                                        @foreach ( DB::table('view_pengadaan')->get() as $values)
                                                                            @if ( $values->idpengadaan == $value->idpengadaan )
                                                                            <option value="{{ $values->idpengadaan }}">{{ $values->idpengadaan }} - ID User ( {{ $values->user_iduser }} ) - ID Vendor ( {{ $values->vendor_idvendor }} ) - Status ( {{ $values->status }} )</option>
                                                                            @endif
                                                                        @endforeach
                                                                        @foreach ( DB::table('view_pengadaan')->get() as $values)
                                                                            @if ( $values->idpengadaan !== $value->idpengadaan )
                                                                            <option value="{{ $values->idpengadaan }}">{{ $values->idpengadaan }} - ID User ( {{ $values->user_iduser }} ) - ID Vendor ( {{ $values->vendor_idvendor }} ) - Status ( {{ $values->status }} )</option>
                                                                            @endif
                                                                        @endforeach

                                                                    </select>
                                                                </div>
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
                                                                    <label for="exampleSelectGender">Status</label>
                                                                    <select name="status" class="form-control" id="exampleSelectGender">
                                                                    <option value=""></option>
                                                                            <option value="P">P - Pending</option>
                                                                            <option value="A">A - Approved</option>
                                                                    </select>
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
                <h4 class="card-title">Tambah Penerimaan</h4>
                <form class="forms-sample" action="{{ route('penerimaan.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="exampleSelectGender">ID Pengadaan</label>
                    <select name="idpengadaan" class="form-control select-id" id="exampleSelectGender">
                        <option value=""></option>
                        @foreach ( DB::table('view_pengadaan')->get() as $value)
                            <option value="{{ $value->idpengadaan }}">
                                {{ $value->idpengadaan }} - ID User ( {{ $value->user_iduser }} ) - ID Vendor ( {{ $value->vendor_idvendor }} ) - Status ( {{ $value->status }} )
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="exampleSelectGender">ID User</label>
                    <select name="iduser" class="form-control" id="exampleSelectGender">
                    <option value=""></option>
                        @foreach ( DB::table('view_user')->get() as $value)
                            <option value="{{ $value->id }}">{{ $value->id }} - {{ $value->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="exampleSelectGender">Status</label>
                    <select name="status" class="form-control" id="exampleSelectGender">
                    <option value=""></option>
                            <option value="P">P - Pending</option>
                            <option value="A">A - Approved</option>
                    </select>
                </div>

                <div id="dynamicFields">
                    <div class="form-row dynamic-item">
                        <div class="form-group col-md-6">
                        <label for="idBarang">ID Barang</label>
                            <select name="idbarang[]" class="form-control idBarang" id="idBarang">
                                <option value="">Pilih ID Pengadaan terlebih dahulu</option>
                                @foreach ($barang as $item)
                                    <option value="{{ $item->idbarang }}">{{ $item->idbarang }} - {{ $item->nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="jumlah">Jumlah Penerimaan</label>
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
    $(document).ready(function() {

    $('.select-id').change(function() { 
        var idpengadaan = $(this).val();
        console.log(idpengadaan); 
        if (idpengadaan) {
            $.ajax({
                url: '/get-barang-by-pengadaan/' + idpengadaan,
                type: 'GET',
                success: function(data) {
                    console.log(data); 
                    var options = '<option value=""></option>';
                    $.each(data, function(index, item) {
                        options += '<option value="'+item.idbarang+'">'+item.idbarang+' - Nama Barang ('+item.nama+') - Satuan ('+item.nama_satuan+')'+'</option>';
                    });
                    $('#idBarang').html(options); 
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error: ' + status + ': ' + error);
                    alert('Terjadi kesalahan saat memuat data.');
                }
            });
        } else {
            $('#idBarang').html('<option value=""></option>');
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
                    jumlahInput.attr('placeholder', 'Jumlah barang yang ada pada pengadaan : ' + response.max);
                },
                error: function() {
                    alert('Terjadi kesalahan saat mengambil data.');
                }
            });
        }
    });

        const dynamicFields = document.getElementById('dynamicFields');
        dynamicFields.addEventListener('click', function(event) {
            if (event.target.classList.contains('add-item')) {
                const newItem = document.querySelector('.dynamic-item').cloneNode(true);
                newItem.querySelectorAll('input, select').forEach(element => {
                    element.value = '';
                });
                dynamicFields.appendChild(newItem);
            }

            if (event.target.classList.contains('remove-item')) {
                const items = document.querySelectorAll('.dynamic-item');
                if (items.length > 1) {
                    event.target.closest('.dynamic-item').remove();
                } else {
                    alert('Tidak dapat menghapus semua item!');
                }
            }
        });
    });

</script>

</div>
