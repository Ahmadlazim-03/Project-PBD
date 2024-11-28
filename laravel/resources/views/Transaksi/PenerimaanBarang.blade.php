@extends('index')

@section('content')

    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
        <div class="card-body">
        <div class="d-flex justify-content-between align-items-center ">
            <h4 class="card-title mb-0">Table Penerimaan</h4>
            <a href="{{ route('detail-penerimaan.index') }}"><button type="button" class="btn btn-success btn-rounded btn-fw" >Detail Penerimaan</button></a>
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
                        </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
        </div>
        </div>
    </div>
@endsection