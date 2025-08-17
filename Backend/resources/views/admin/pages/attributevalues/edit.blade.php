@extends('admin.layouts.master')
@section('title', 'Sửa đổi')
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
                <div class="card-title">Sửa AttributeValues</div>
            </div>
            <div class="card-body">
                <form action="{{ route('attribute_values.update', $attributeValue->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('put')
                    <div class="mb-3">
                        <label for="" class="form-lable">Attribute:</label>
                        <select name="attribute_id" class="form-control">
                            @foreach ($attributes as $attribute)
                                <option value="{{ $attribute->id }}" {{ $attributeValue->attribute_id == $attribute->id ? 'selected' : '' }}>{{ $attribute->name }}</option>
                            @endforeach
                        </select>
                        @error('attribute_id')
                            <div style="color: red">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Tên:</label><br>
                        <input type="text" name="value" value="{{ $attributeValue->value }}" class="form-control mb-2"
                            placeholder="Mời nhập tên Attribute">
                        @error('value')
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
