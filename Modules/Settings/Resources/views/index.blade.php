@extends('settings::layouts.master')

@section('content')
    <div class="container">

        <div class="overflow-hidden">
            {{--TODO: Make Blade Component--}}
            <h1 class="text-left mb-3 font-bold text-indigo-500 font-mono uppercase">
                <x-heroicon-o-finger-print width="20" class="float-left mr-2" />
                Permissions & Roles
            </h1>
            <div class="columns-3">
                <h4 class="font-extrabold">Roles:</h4>
                <ul>
                    @foreach($data['roles'] as $role)
                        <li class="float-left mr-2 text-center">{{ $role->name }}</li>
                    @endforeach
                </ul>
            </div>
            <div class="w-100 float-left">
                <h4 class="font-extrabold">Permissions:</h4>
                <ul class="pl-2 text-left">
                    @foreach($data['permissions'] as $permission)
                        <li>{{ ucwords(str_replace('_', ' ', $permission->name)) }}</li>
                    @endforeach
                </ul>
            </div>
            <div class="float-left w-[calc(90%)]">
                <div class="overflow-hidden w-[calc(50%)] m-auto">

                @foreach($data['roles'] as $role)
                    @php
                        $getRolePermission = $data['roles_has_permissions']
                            ->filter(function ($item) use ($role) {
                                if ($role->name === $item->role) {
                                    return $item;
                                }
                            });
                    @endphp
                        <ul class="mt-6 ml-8 mr-6 float-left">
                            @foreach($data['permissions'] as $permission)
                                @php
                                    $result = false;
                                    foreach ($getRolePermission as $item) {
                                        if ($item->permission === $permission->name) {
                                            $result = true;
                                        }
                                }
                            @endphp
                            <li class="cursor-pointer">
                                <v-change-status status="{{$result}}"
                                                 permission_id="{{ $permission->id }}"
                                                 role_id="{{ $role->id }}" />
                                    @if($result)
                                        <x-heroicon-o-check-circle style="width: 24px;" fill="green"></x-heroicon-o-check-circle>
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                             stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M15 12H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                        </svg>
                                    @endif
                                </v-change-status>
                            </li>

                            @endforeach
                        </ul>
                @endforeach
                </div>

            </div>
        </div>
    </div>
@endsection
