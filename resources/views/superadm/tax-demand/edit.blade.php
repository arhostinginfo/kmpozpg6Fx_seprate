@extends('superadm.layout.master')

@section('title', 'Edit Tax Demand')

@section('content')
<div class="row">
    <div class="col-lg-7 col-md-9 mx-auto">
        <div class="card">
            <div class="card-body">
                <h4 class="mb-3">कर मागणी संपादित करा</h4>

                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <form action="{{ route('tax-demand.update') }}" method="POST">
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
                        <label>वर्ष प्रकार <span class="text-danger">*</span></label>
                        <select name="year_type" class="form-control" required>
                            <option value="chalu" {{ old('year_type', $data->year_type) == 'chalu' ? 'selected' : '' }}>चालू वर्ष</option>
                            <option value="magil" {{ old('year_type', $data->year_type) == 'magil' ? 'selected' : '' }}>मागील वर्ष</option>
                        </select>
                        @error('year_type')<span class="text-danger">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-group mb-3">
                        <label>मागणी रक्कम (₹) <span class="text-danger">*</span></label>
                        <input type="number" name="demand_amount" step="0.01" min="0"
                            class="form-control" value="{{ old('demand_amount', $data->demand_amount) }}" required>
                        @error('demand_amount')<span class="text-danger">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-group mb-3">
                        <label>वसूल रक्कम (₹) <span class="text-danger">*</span></label>
                        <input type="number" name="collected_amount" step="0.01" min="0"
                            class="form-control" value="{{ old('collected_amount', $data->collected_amount) }}" required>
                        @error('collected_amount')<span class="text-danger">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-group mb-3">
                        <label>सध्याची टक्केवारी</label>
                        <input type="text" class="form-control" value="{{ $data->percentage }}%" readonly
                            style="background:#f8f9fa; font-weight:600;">
                        <small class="text-muted">सेव्ह केल्यावर आपोआप अपडेट होईल.</small>
                    </div>

                    <div class="form-group mb-3">
                        <label>स्थिती</label>
                        <select name="is_active" class="form-control" required>
                            <option value="1" {{ old('is_active', $data->is_active) == 1 ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('is_active', $data->is_active) == 0 ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <div class="form-group d-flex justify-content-end gap-2">
                        <a href="{{ route('tax-demand.list') }}" class="btn btn-secondary">रद्द करा</a>
                        <button type="submit" class="btn btn-success">अपडेट करा</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
