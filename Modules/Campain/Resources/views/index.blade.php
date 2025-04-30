@extends('campain::layouts.master')

@section('content')
    <v-page-campains :content="{{ json_encode($api_campains) }}" />
@endsection
