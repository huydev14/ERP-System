@extends('layouts.main')

@section('content')
    <table id="users-table" class="display" style="width:100%">
        <thead>
            <tr>
                <th>Id</th> {{-- Use "Id" if using addIndexColumn() for the first column, or the actual DB column name --}}
                <th>Name</th>
                <th>Email</th>
                <th>Created At</th>
                <th>Updated At</th>
            </tr>
        </thead>
    </table>


    <script type="module">
        $(function() {
            $('#users-table').DataTable({
                processing: true,
                serverSide: true, // Enables server-side processing
                ajax: '{!! route('users.data') !!}', // Route to fetch data
                columns: [
                    // Adjust column definitions to match your data
                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'updated_at',
                        name: 'updated_at'
                    },
                ]
            });
        });
    </script>
@endsection
