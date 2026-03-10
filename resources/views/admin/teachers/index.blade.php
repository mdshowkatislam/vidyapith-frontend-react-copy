@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif
                    <h3>Teachers</h3>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">institute</th>
                                <th scope="col">pdsid</th>
                                <th scope="col">institute type</th>
                                <th scope="col">index number</th>
                                <th scope="col">institute name</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($teachers as $teacher)
                            <tr>
                                <th scope="row">{{ $teacher->id }}</th>
                                <td>{{ $teacher->institute_id }}</td>
                                <td>{{ $teacher->pdsid  }}</td>
                                <td>{{ $teacher->institute_type }}</td>
                                <td>{{ $teacher->index_number }}</td>
                                <td>{{ $teacher->institute_name }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="card-body">
                    <h3>Emis Teachers</h3>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">pdsid</th>
                                <th scope="col">pdsid</th>
                                <th scope="col">institute type</th>
                                <th scope="col">index number</th>
                                <th scope="col">institute name</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($emis_teacher as $teache)
                            <tr>
                                <th scope="row">{{ $teache->pdsid }}</th>
                                <td>{{ $teache->pdsid }}</td>
                                <td>{{ $teache->pdsid }}</td>
                                <td>{{ $teache->managementtype }}</td>
                                <td>{{ $teache->indexnumber }}</td>
                                <td>{{ $teache->institutename }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection