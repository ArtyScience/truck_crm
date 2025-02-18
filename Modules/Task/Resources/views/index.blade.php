<?php extract($data); ?>
@extends('task::layouts.master')
@section('content')
    <v-page-tasks :data="{{ $tasks }}"></v-page-tasks>
@endsection
