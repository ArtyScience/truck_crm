@extends('user::layouts.master')

@section('content')
    <v-page-user :data="{{ json_encode($users) }}"
                 :roles="{{ json_encode($roles) }}" />
@endsection
