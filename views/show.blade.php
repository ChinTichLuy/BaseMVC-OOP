@extends('main')

@section('title')
    Xem Chi Tiet
@endsection

@section('content')
    <div class="table-responsive">
        <table class="table table-primary">
            <thead>
                <tr>
                    <th scope="col">Truong du lieu</th>
                    <th scope="col">Gia tri</th>
                </tr>
            </thead>
            <tbody>

                @foreach ($product as $key => $value)
                    <tr class="">
                        <td scope="row">{{ strtoupper($key) }}</td>
                        <td>
                            @switch($key)
                                @case('img_thumbnail')
                                    <img src="{{ file_url($value) }}" width="100px" alt="">
                                @break

                                @default
                                    {{ $value }}
                            @endswitch
                        </td>
                    </tr>
                @endforeach

            </tbody>
            <a href="/products" class="btn btn-warning">Back to list</a>
        </table>
    </div>
@endsection
