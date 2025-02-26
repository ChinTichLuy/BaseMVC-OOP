@extends('main')

@section('title')
    Cap nhat san pham
@endsection

@section('content')
    <div class="container">

        @if (!empty($_SESSION['errors']))
            <div class="alert alert-danger">
                @foreach ($_SESSION['errors'] as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
            @php
                unset($_SESSION['errors']);
            @endphp
        @endif

        <form action="/products/{{ $product['id'] }}/update" method="POST" enctype="multipart/form-data">
            <div class="mb-3 row">
                <label for="category_id" class="col-4 col-form-label">Category</label>
                <div class="col-8">
                    <select class="form-control" name="category_id" id="category_id">
                        <option value="">Chon danh muc</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category['id'] }}" @selected($product['category_id'] == $category['id'])>{{ $category['name'] }}
                            </option>
                        @endforeach
                    </select>

                </div>
            </div>
            <div class="mb-3 row">
                <label for="name" class="col-4 col-form-label">Name</label>
                <div class="col-8">
                    <input type="text" class="form-control" name="name" id="name"
                        value="{{ $product['name'] }}" />
                </div>
            </div>
            <div class="mb-3 row">
                <label for="img_thumbnail" class="col-4 col-form-label">IMG THUMBNAIL</label>
                <div class="col-8">
                    <input type="file" class="form-control" name="img_thumbnail" id="img_thumbnail" />
                    @if ($product['img_thumbnail'])
                        <img src="{{ file_url($product['img_thumbnail']) }}" width="100px" alt="">
                    @endif
                </div>
            </div>
            <div class="mb-3 row">
                <label for="description" class="col-4 col-form-label">Description</label>
                <div class="col-8">
                    <textarea class="form-control" name="description" id="description">{{ $product['description'] }}</textarea>
                </div>
            </div>

            <div class="mb-3 row">
                <div class="offset-sm-4 col-sm-8">
                    <button type="submit" class="btn btn-primary">
                        Submit
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection
