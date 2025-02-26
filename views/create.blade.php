@extends('main')

@section('title')
    Them Moi
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

        <form action="/products/store" method="POST" enctype="multipart/form-data">
            <div class="mb-3 row">
                <label for="category_id" class="col-4 col-form-label">Category</label>
                <div class="col-8">
                    <select class="form-control" name="category_id" id="category_id">
                        <option value="">Chon danh muc</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category['id'] }}">{{ $category['name'] }}</option>
                        @endforeach
                    </select>

                </div>
            </div>
            <div class="mb-3 row">
                <label for="name" class="col-4 col-form-label">Name</label>
                <div class="col-8">
                    <input type="text" class="form-control" name="name" id="name" placeholder="Name" />
                </div>
            </div>
            <div class="mb-3 row">
                <label for="img_thumbnail" class="col-4 col-form-label">IMG THUMBNAIL</label>
                <div class="col-8">
                    <input type="file" class="form-control" name="img_thumbnail" id="img_thumbnail" />
                </div>
            </div>
            <div class="mb-3 row">
                <label for="description" class="col-4 col-form-label">Description</label>
                <div class="col-8">
                    <textarea class="form-control" name="description" id="description"></textarea>
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
