<?php extract($data) ?>

@extends('deal::layouts.master')

@section('content')
    <v-page-deals :deals="{{ $deals }}"></v-page-deals>
@endsection
