@extends('admin.layouts.master')
@section('title', 'Thêm mới')
@section('css')

    <style>
        .note-icon-caret:before {
            content: "" !important;
        }
    </style>
@endsection
@section('content')
        <div class="card" style="width: 100%">
            <div class="card-header">
                <div class="card-title">Tạo Attribute</div>
            </div>
            <div class="card-body">
                <form action="{{ route('attributes.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Tên Attribute:</label><br>
                        <input type="text" name="name" value="{{ old('name') }}" class="form-control mb-2"
                            placeholder="Mời nhập tên Attribute">
                        @error('name')
                            <div style="color: red">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <input type="submit" value="Submit" class="btn btn-primary mt-2">
                </form>
            </div>
        </div>
@endsection
