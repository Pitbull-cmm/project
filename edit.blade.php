@extends('admin::layout')

@component('admin::components.page.header')
    @slot('title', trans('admin::resource.edit', ['resource' => trans('brand::brands.brands')]))

    <li><a href="{{ route('admin.brands.index') }}">{{ trans('brand::brands.brands') }}</a></li>
    <li class="active">{{ trans('admin::resource.edit', ['resource' => trans('brand::brands.brands')]) }}</li>
@endcomponent
@section('content')
    <form method="POST" action="{{ route('admin.brands.update', $brand->id) }}" class="form-horizontal" id="brand-form">
        @csrf
        @method('PUT')
        <div class="box">
            <div class="box-body">
                <div class="form-group">
                    <div class="tab-pane fade in active" id="general">
                    <h3 class="tab-content-title">{{ trans('brand::brands.general') }}</h3>
                    </div>
                </div>
                <div class="form-group">
                    <label for="file_logo" class="col-md-3 control-label">
                        {{ trans('brand::brands.form.logo') }}<span class="m-l-5 text-red">*</span>
                    </label>
                    <div class="col-md-9">
                        <button type="button" class="btn btn-default" id="custom-file-button">
                            <input type="file" id="file_logo" name="file_logo" class="form-control" value="{{ $brand->file_logo }}">
                            <i class="fa fa-folder-open m-r-5"></i>Browse
                        </button>
                        @if ($errors->has('file_logo'))
                            <div class="alert alert-danger">
                                {{ $errors->first('file_logo') }}
                            </div>
                        @endif
                        <div class="clearfix"></div>
                        <div class="single-image image-holder-wrapper clearfix">
                            @if (!empty($brand->file_logo))
                                <img src="{{ $brand->file_logo }}" alt="Logo" class="image-holder" id="image-preview" style="max-width: 200px; max-height: 200px;">
                            @else
                                <div class="image-holder placeholder" id="image-preview">
                                    <i class="fa fa-picture-o"></i>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="name" class="col-md-3 control-label">{{ trans('brand::brands.form.name') }}<span class="m-l-5 text-red">*</span></label>
                    <div class="col-md-9">
                        <input name="name" id="name" type="text" style="margin-bottom: 10px" class="form-control" value="{{ $brand->name }}">
                        @if ($errors->has('name'))
                            <div class="alert alert-danger">
                                {{ $errors->first('name') }}
                            </div>
                        @endif
                    </div>
                </div>

                <div class="form-group">
                    <label for="is_active" class="col-md-3 control-label">{{ trans('brand::brands.form.status') }}</label>
                    <div class="col-md-9">
                        <div class="checkbox">
                            <input type="hidden" value="0" name="is_active">
                            <input type="checkbox" value="1" name="is_active" id="is_active" {{ $brand->is_active ? 'checked' : '' }}>
                            <label for="is_active">{{ trans('brand::brands.form.enable') }}</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-10 col-md-offset-2">
                <button type="submit" class="btn btn-primary" data-loading>{{ trans('Save') }}</button>
            </div>
        </div>
    </form>
@endsection

@include('brand::admin.brands.partials.shortcuts')

@push('globals')
    @vite([
        'modules/Brand/Resources/assets/admin/sass/app.scss',
        'modules/Brand/Resources/assets/admin/js/create.js',
        'modules/Variation/Resources/assets/admin/sass/main.scss',
        'modules/Option/Resources/assets/admin/sass/main.scss',
        'modules/Media/Resources/assets/admin/sass/main.scss',
        'modules/Media/Resources/assets/admin/js/main.js',
    ])
@endpush
@push('script')
<script>
    $(document).ready(function () {
        const fileInput = $('#file_logo');
        const previewHolder = $('#image-preview');
        let originalPreview = previewHolder.html(); // Lưu hình ảnh ban đầu

        // Hiển thị ảnh xem trước sau khi chọn file
        fileInput.on('change', function (event) {
            const file = event.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function (e) {
                const imagePath = e.target.result;
                previewHolder.html(`<img src="${imagePath}" alt="Brand Logo" class="img-fluid"/>`);
            };
            reader.readAsDataURL(file);
        });

        // Xử lý khi hủy hoặc không lưu
        $('.cancel-button').on('click', function () {
            previewHolder.html(originalPreview); // Hiển thị lại hình ảnh cũ
            fileInput.val(""); // Reset input file
        });


        // Xóa ảnh đã chọn
        $(document).on('click', '.remove-image', function () {
            previewHolder.html(`<i class="fa fa-picture-o"></i>`);
            fileInput.val("");
        });
    });
</script>

@endpush
