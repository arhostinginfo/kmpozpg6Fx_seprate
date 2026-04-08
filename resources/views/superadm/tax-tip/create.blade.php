@extends('superadm.layout.master')

@section('title', 'Add Tax Tip')

@section('content')
<div class="row">
    <div class="col-lg-8 col-md-10 mx-auto">
        <div class="card">
            <div class="card-body">
                <h4 class="mb-1">नवीन कर टीप जोडा</h4>
                <p class="text-muted mb-3" style="font-size:0.85rem;">
                    ही टीप वेबसाईटवर कर विभागाच्या खाली नारंगी बॉक्समध्ये दिसेल.
                </p>

                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <form action="{{ route('tax-tip.save') }}" method="POST">
                    @csrf

                    <div class="form-group mb-3">
                        <label>टीप मजकूर <span class="text-danger">*</span></label>
                        <textarea name="tip_text" class="form-control" rows="4"
                            placeholder="उदा: कर भरण्यासाठी ग्रामपंचायत कार्यालयात संपर्क करा. वेळ: सोम-शुक्र सकाळी ९ ते सायंकाळी ५."
                            required maxlength="1000">{{ old('tip_text') }}</textarea>
                        <small class="text-muted">कमाल १००० अक्षरे</small>
                        @error('tip_text')<span class="text-danger">{{ $message }}</span>@enderror
                    </div>

                    {{-- Live Preview --}}
                    <div class="mb-3">
                        <label class="form-label">Preview (वेबसाईटवर असे दिसेल)</label>
                        <div id="tipPreview" class="p-3 rounded" style="background-color:#f4a261; color:#333; font-size:0.9rem; min-height:48px;">
                            <strong>टीप:-</strong> <span id="previewText">येथे मजकूर दिसेल...</span>
                        </div>
                    </div>

                    <div class="form-group d-flex justify-content-end gap-2">
                        <a href="{{ route('tax-tip.list') }}" class="btn btn-secondary">रद्द करा</a>
                        <button type="submit" class="btn btn-primary">जतन करा</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    const textarea    = document.querySelector('textarea[name="tip_text"]');
    const previewText = document.getElementById('previewText');
    textarea.addEventListener('input', function() {
        previewText.textContent = this.value || 'येथे मजकूर दिसेल...';
    });
</script>
@endsection
