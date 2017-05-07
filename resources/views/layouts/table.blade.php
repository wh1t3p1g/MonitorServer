@extends("layouts.admin")



@section("script")
    <script src="{{ asset("plugins/datatables/jquery.dataTables.min.js") }}"></script>
    <script src="{{ asset("plugins/datatables/dataTables.bootstrap.min.js") }}"></script>
    @yield("tScript")
@endsection

@section("css")
    <link rel="stylesheet" href="{{ asset("plugins/datatables/dataTables.bootstrap.css") }}">
@endsection