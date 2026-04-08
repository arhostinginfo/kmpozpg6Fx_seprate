@extends('superadm.layout.master')

@section('title', 'Edit Tax Document')

@section('content')
<div class="row">
    <div class="col-lg-7 col-md-9 mx-auto">
        <div class="card">
            <div class="card-body">
                <h4 class="mb-3">कर दस्तऐवज संपादित करा</h4>

                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <form action="{{ route('tax-document.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{{ base64_encode($data->id) }}">

                    <div class="form-group mb-3">
                        <label>कराचा प्रकार <span class="text-danger">*</span></label>
                        <select name="tax_type" class="form-control" required>
                            <option value="ghar_patti"  {{ old('tax_type', $data->tax_type) == 'ghar_patti'  ? 'selected' : '' }}>घरपट्टी कर</option>
                            <option value="paani_patti" {{ old('tax_type', $data->tax_type) == 'paani_patti' ? 'selected' : '' }}>पाणीपट्टी कर</option>
                            <option value="other"       {{ old('tax_type', $data->tax_type) == 'other'       ? 'selected' : '' }}>गाळाभाडे / व्यवसायकर / इतर</option>
                        </select>
                        @error('tax_type')<span class="text-danger">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-group mb-3">
                        <label>दस्तऐवज प्रकार <span class="text-danger">*</span></label>
                        <select name="document_type" class="form-control" required>
                            <option value="view_pdf"   {{ old('document_type', $data->document_type) == 'view_pdf'   ? 'selected' : '' }}>PDF पहा (कर यादी)</option>
                            <option value="payment_qr" {{ old('document_type', $data->document_type) == 'payment_qr' ? 'selected' : '' }}>QR कोड (पेमेंट)</option>
                        </select>
                        @error('document_type')<span class="text-danger">{{ $message }}</span>@enderror
                    </div>

                    {{-- Current file --}}
                    <div class="form-group mb-3">
                        <label>सध्याची फाईल</label><br>
                        @if($data->isImage())
                            <img src="{{ asset('storage/' . $data->file_path) }}"
                                style="height:80px; border-radius:6px; border:1px solid #ddd;">
                        @else
                            <a href="{{ asset('storage/' . $data->file_path) }}" target="_blank"
                                class="btn btn-sm btn-outline-danger">
                                <i class="mdi mdi-file-pdf-box"></i> {{ $data->original_name ?? 'PDF पहा' }}
                            </a>
                        @endif
                    </div>

                    <div class="form-group mb-3">
                        <label>नवीन फाईल (ऐच्छिक)</label>
                        <input type="file" name="file" class="form-control" id="fileInput"
                            accept=".pdf,.jpg,.jpeg,.png,.gif,.webp">
                        <small class="text-muted">रिकामे सोडल्यास जुनी फाईल ठेवली जाईल.</small>
                        @error('file')<span class="text-danger">{{ $message }}</span>@enderror
                    </div>

                    <div id="previewContainer" class="mb-3" style="display:none;">
                        <label>New File Preview:</label><br>
                        <img id="imgPreview" src="" style="max-height:100px; border-radius:6px; border:1px solid #ddd; display:none;">
                        <span id="pdfPreview" style="display:none;">
                            <i class="mdi mdi-file-pdf-box text-danger" style="font-size:2rem;"></i>
                            <span id="pdfName" class="ms-1 text-muted" style="font-size:0.85rem;"></span>
                        </span>
                    </div>

                    <div class="form-group mb-3">
                        <label>स्थिती</label>
                        <select name="is_active" class="form-control" required>
                            <option value="1" {{ old('is_active', $data->is_active) == 1 ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('is_active', $data->is_active) == 0 ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <div class="form-group d-flex justify-content-end gap-2">
                        <a href="{{ route('tax-document.list') }}" class="btn btn-secondary">रद्द करा</a>
                        <button type="submit" class="btn btn-success">अपडेट करा</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('fileInput').addEventListener('change', function () {
    const file = this.files[0];
    if (!file) return;
    const container  = document.getElementById('previewContainer');
    const imgPreview = document.getElementById('imgPreview');
    const pdfPreview = document.getElementById('pdfPreview');
    const pdfName    = document.getElementById('pdfName');
    container.style.display = 'block';
    if (file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = e => { imgPreview.src = e.target.result; };
        reader.readAsDataURL(file);
        imgPreview.style.display = 'inline-block';
        pdfPreview.style.display = 'none';
    } else {
        imgPreview.style.display = 'none';
        pdfPreview.style.display = 'inline-block';
        pdfName.textContent = file.name;
    }
});
</script>
@endsection
