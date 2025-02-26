@extends('admin.layouts.main')

@section('title')
    {{ $title }}
@endsection

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $title }}</li>
        </ol>
    </nav>
    <h1 class="h2">{{ $title }}</h1>
    <div class="row">
        <div class="col-12 mb-4 mb-lg-0">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">Truong du lieu</th>
                                    <th scope="col">Gia tri</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($user as $field => $value)
                                    <tr>

                                        <td>{{ strtoupper($field) }}</td>
                                        <td>
                                            @switch($field)
                                                @case('avatar')
                                                    <img src="{{ file_url($value) }}" width="100px" alt="">
                                                @break

                                                @case('type')
                                                    @if ($value == 'admin')
                                                        <span class="badge bg-primary">Admin</span>
                                                    @else
                                                        <span class="badge bg-secondary">Client</span>
                                                    @endif
                                                @break

                                                @case('password')
                                                    **********
                                                @break

                                                @default
                                                    {{ $value }}
                                            @endswitch
                                        </td>

                                    </tr>
                                @endforeach

                                <a href="/admin/users" class="btn btn-warning">
                                    Back to list
                                </a>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endsection
