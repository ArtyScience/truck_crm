@php
extract($data)
@endphp

@extends('leads::layouts.master')

@section('content')
    <v-page-leads :data="{{ $leads }}" :options="{{ $comodities }}" :deals="{{ $deals }}" />
@endsection
