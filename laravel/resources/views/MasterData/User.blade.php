@extends('index')

@section('content')
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
        <div class="card-body">
            <h4 class="card-title">Table User</h4>
            <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>ID User</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Id Role</th>
                </tr>
                </thead>
                <tbody>
                    @foreach ( $TableUser as $value )
                    <tr>
                            <td>
                             {{ $value->id }}
                            </td>
                            <td>
                             {{ $value->name }}
                            </td>
                            <td>
                             {{ $value->email }}
                            </td>
                            <td> {{ $value->role_id }} </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
        </div>
        </div>
    </div>
@endsection