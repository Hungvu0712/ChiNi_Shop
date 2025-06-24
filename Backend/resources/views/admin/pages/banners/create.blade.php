@extends('admin.layouts.master')
@section('title', 'Thêm mới banner')
@section('css')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-bs4.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
<style>
    :root {
        --primary-color: #6366f1;
        --primary-hover: #4f46e5;
        --secondary-color: #f8fafc;
        --text-color: #1e293b;
        --light-gray: #e2e8f0;
        --border-radius: 8px;
        --box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
    
    body {
        background-color: #f8fafc;
    }
    
    .card {
        border: none;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        overflow: hidden;
        background-color: white;
    }
    
    .card-header {
        background-color: white;
        border-bottom: 1px solid var(--light-gray);
        padding: 1.5rem;
    }
    
    .card-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--text-color);
        margin: 0;
    }
    
    .form-label {
        font-weight: 500;
        color: var(--text-color);
        margin-bottom: 0.75rem;
        display: block;
    }
    
    .form-control {
        border-radius: var(--border-radius);
        border: 1px solid var(--light-gray);
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
        background-color: white;
    }
    
    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
    }
    
    .btn {
        border-radius: var(--border-radius);
        padding: 0.75rem 1.5rem;
        font-weight: 500;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .btn-primary {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }
    
    .btn-primary:hover {
        background-color: var(--primary-hover);
        border-color: var(--primary-hover);
        transform: translateY(-1px);
    }
    
    .btn-outline-secondary {
        border-color: var(--light-gray);
        color: var(--text-color);
    }
    
    .btn-outline-secondary:hover {
        background-color: var(--secondary-color);
        border-color: var(--light-gray);
    }
    
    .error-message {
        color: #ef4444;
        font-size: 0.875rem;
        margin-top: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }
    
    .note-editor.note-frame {
        border-radius: var(--border-radius) !important;
        border: 1px solid var(--light-gray) !important;
        box-shadow: none !important;
    }
    
    .file-upload-wrapper {
        position: relative;
        margin-bottom: 1rem;
    }
    
    .file-upload-label {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 2rem;
        border: 2px dashed var(--light-gray);
        border-radius: var(--border-radius);
        background-color: white;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .file-upload-label:hover {
        border-color: var(--primary-color);
        background-color: rgba(99, 102, 241, 0.05);
    }
    
    .file-upload-icon {
        font-size: 2rem;
        color: var(--primary-color);
        margin-bottom: 1rem;
    }
    
    .file-upload-text {
        font-size: 1rem;
        color: var(--text-color);
        margin-bottom: 0.5rem;
    }
    
    .file-upload-hint {
        font-size: 0.875rem;
        color: #64748b;
    }
    
    .file-upload-input {
        position: absolute;
        left: 0;
        top: 0;
        opacity: 0;
        width: 100%;
        height: 100%;
        cursor: pointer;
    }
    
    .preview-image {
        max-width: 100%;
        max-height: 200px;
        border-radius: var(--border-radius);
        margin-top: 1rem;
        display: none;
    }
</style>
@endsection

@section('content')
<div class="container my-4">
    <div class="row">
        <div class="col-lg-16">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="card-title">Tạo banner mới</div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('banners.store') }}" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                        @csrf
                        
                        <div class="mb-4">
                            <label for="title" class="form-label">Tiêu đề banner</label>
                            <input type="text" name="title" value="{{ old('title') }}" 
                                class="form-control" 
                                placeholder="Nhập tiêu đề banner"
                                required>
                            @error('title')
                                <div class="error-message">
                                    <i class="bi bi-exclamation-circle"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label">Hình ảnh banner</label>
                            <div class="file-upload-wrapper">
                                <label class="file-upload-label">
                                    <i class="bi bi-cloud-arrow-up file-upload-icon"></i>
                                    <span class="file-upload-text">Kéo thả hình ảnh vào đây hoặc click để chọn</span>
                                    <span class="file-upload-hint">Định dạng: JPG, PNG, GIF. Kích thước tối đa: 2MB</span>
                                    <input type="file" name="banner_image" class="file-upload-input" accept="image/*">
                                </label>
                                <img id="imagePreview" class="preview-image" src="#" alt="Preview">
                            </div>
                            @error('banner_image')
                                <div class="error-message">
                                    <i class="bi bi-exclamation-circle"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="link" class="form-label">Liên kết (URL)</label>
                            <input type="url" name="link" value="{{ old('link') }}" 
                                class="form-control" 
                                placeholder="https://example.com">
                            @error('link')
                                <div class="error-message">
                                    <i class="bi bi-exclamation-circle"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="content" class="form-label">Nội dung</label>
                            <textarea name="content" id="summernote" rows="10" 
                                class="form-control @error('content') is-invalid @enderror" 
                                placeholder="Nhập nội dung banner">{{ old('content') }}</textarea>
                            @error('content')
                                <div class="error-message">
                                    <i class="bi bi-exclamation-circle"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        <div class="d-flex justify-content-end gap-3 mt-5">
                        <a href="{{ route('banners.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> Quay lại
                        </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Lưu banner
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-bs4.min.js"></script>
<script>
    $(document).ready(function() {
        // Summernote initialization
        $('#summernote').summernote({
            height: 300,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'strikethrough', 'clear']],
                ['fontname', ['fontname']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video', 'hr']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ],
            fontSizes: ['8', '9', '10', '11', '12', '14', '16', '18', '24', '36', '48'],
            placeholder: 'Nhập nội dung banner tại đây...',
            dialogsInBody: true
        });
        
        // Image preview functionality
        $('.file-upload-input').change(function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#imagePreview').attr('src', e.target.result).show();
                }
                reader.readAsDataURL(file);
            }
        });
        
        // Drag and drop functionality
        $('.file-upload-label').on('dragover', function(e) {
            e.preventDefault();
            $(this).addClass('dragover');
        });
        
        $('.file-upload-label').on('dragleave', function(e) {
            e.preventDefault();
            $(this).removeClass('dragover');
        });
        
        $('.file-upload-label').on('drop', function(e) {
            e.preventDefault();
            $(this).removeClass('dragover');
            const file = e.originalEvent.dataTransfer.files[0];
            if (file) {
                $('.file-upload-input')[0].files = e.originalEvent.dataTransfer.files;
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#imagePreview').attr('src', e.target.result).show();
                }
                reader.readAsDataURL(file);
            }
        });
    });
</script>
@endsection