@php extract($data) @endphp

@extends('dashboard::layouts.master')

@section('content')
    <v-page-layout>
        <v-page-dashboard
            :tasks_list="{{ json_encode($tasks_list) }}"
            :deals_list="{{ json_encode($deal_list) }}"
            :status_list="{{ json_encode($status_list) }}"
            :activity_per_region="{{ json_encode($region_activity)}}"
            :leads_week_statistic="{{ json_encode($leads_week_statistic)}}"
            :deals_week_statistic="{{ json_encode($deals_week_statistic)}}"
            :call_logs="{{ json_encode($call_logs) }}"
            :user_role="{{ json_encode($role) }}"
        />
    </v-page-layout>
@endsection
