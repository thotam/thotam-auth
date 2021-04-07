@extends('layouts.layout-2')
@section('styles')
    <link rel="stylesheet" href="{{ mix('/vendor/libs/datatables/datatables.css') }}">
    <link rel="stylesheet" href="{{ mix('/vendor/libs/select2/select2.css') }}">
@endsection

@section('scripts')
    <!-- Dependencies -->
    <script src="{{ mix('/vendor/libs/datatables/datatables.js') }}"></script>
    <script src="{{ mix('/vendor/libs/select2/select2.js') }}"></script>
@endsection

@push('datatables')
    <script src="{{ asset('vendor/datatables/buttons.server-side.js') }}"></script>
    {{$dataTable->scripts()}}
@endpush

@section('content')
    <h4 class="font-weight-bold py-3 mb-4">{{ $title }}</h4>

    <div class="card pt-3">

        @livewire('thotam-auth::auth-livewire')

        <div class="card-datatable table-responsive pt-2">
            {{$dataTable->table(["thotam-datatables", "class" => "table table-striped table-bordered", "width" => "100%"])}}
        </div>

    </div>

@endsection
