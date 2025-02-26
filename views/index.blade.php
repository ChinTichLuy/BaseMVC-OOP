@extends('main')

@section('title')
    Danh sach
@endsection

@section('content')
    <a href="/products/create" class="btn btn-success">ADD NEW</a>

    <div class="table-responsive">
        <table class="table table-primary">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">IMG THUMBNAIL</th>
                    <th scope="col">CATEGORY</th>
                    <th scope="col">CREATED AT</th>
                    <th scope="col">UPDATED AT</th>
                    <th scope="col">ACTION</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $product)
                    <tr class="">
                        <td scope="row">{{ $product['id'] }}</td>
                        <td>{{ $product['name'] }}</td>
                        <td>
                            @if ($product['img_thumbnail'])
                                <img src="{{ file_url($product['img_thumbnail']) }}" width="100px" alt="">
                            @endif
                        </td>
                        <td>{{ $product['c_name'] }}</td>
                        <td>{{ $product['created_at'] }}</td>
                        <td>{{ $product['updated_at'] }}</td>
                        <td>
                            <a href="/products/{{ $product['id'] }}/show" class="btn btn-info">Show</a>
                            <a href="/products/{{ $product['id'] }}/edit" class="btn btn-warning">Edit</a>
                            <a href="/products/{{ $product['id'] }}/delete"
                                onclick="return confirm('Are you sure about that?')" class="btn btn-danger">Delete</a>
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
