@extends('superadm.layout.master')

@section('title', 'Tax Tip')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <div class="mb-3 d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0">कर टीप व्यवस्थापन</h4>
                        <small class="text-muted">वेबसाईटवर कर विभागाखाली दिसणारी टीप/सूचना</small>
                    </div>
                    <a href="{{ route('tax-tip.create') }}" class="btn btn-sm btn-outline-primary">+ नवीन टीप जोडा</a>
                </div>

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                {{-- Info box --}}
                <div class="alert alert-info py-2 mb-3" style="font-size:0.85rem;">
                    <i class="mdi mdi-information-outline me-1"></i>
                    वेबसाईटवर फक्त <strong>एकच Active</strong> टीप दिसते — सर्वात नवीन Active टीप प्रदर्शित केली जाते.
                    जुन्या टीपा Inactive ठेवा किंवा हटवा.
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped datatables">
                        <thead>
                            <tr>
                                <th>अ.नं.</th>
                                <th>टीप मजकूर</th>
                                <th>स्थिती</th>
                                <th>दिनांक</th>
                                <th>कृती</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($tips as $key => $tip)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td style="max-width:400px;">
                                        <div style="white-space:pre-wrap; font-size:0.88rem;">{{ $tip->tip_text }}</div>
                                    </td>
                                    <td>
                                        <form action="{{ route('tax-tip.updatestatus') }}" method="POST" class="d-inline-block">
                                            @csrf
                                            <label class="switch">
                                                <input type="checkbox" class="toggle-status"
                                                    data-id="{{ base64_encode($tip->id) }}"
                                                    {{ $tip->is_active == 1 ? 'checked' : '' }}>
                                                <span class="slider"></span>
                                            </label>
                                            <input type="hidden" name="id" value="{{ base64_encode($tip->id) }}">
                                        </form>
                                    </td>
                                    <td style="font-size:0.82rem; white-space:nowrap;">
                                        {{ $tip->created_at->format('d M Y') }}
                                    </td>
                                    <td style="white-space:nowrap;">
                                        <a href="{{ route('tax-tip.edit', base64_encode($tip->id)) }}"
                                            class="btn btn-sm btn-outline-primary">Edit</a>
                                        <form action="{{ route('tax-tip.delete') }}" method="POST" class="d-inline-block">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ base64_encode($tip->id) }}">
                                            <button type="button" class="btn btn-sm btn-outline-danger delete-btn">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        <i class="mdi mdi-information-outline d-block mb-1" style="font-size:1.5rem;"></i>
                                        कोणतीही टीप नोंदवलेली नाही. वरील बटणावर क्लिक करून नवीन टीप जोडा.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    $(document).on("change", ".toggle-status", function (e) {
        e.preventDefault();
        let checkbox  = $(this);
        let form      = checkbox.closest("form");
        let is_active = checkbox.is(":checked") ? 1 : 0;
        Swal.fire({
            title: "Are you sure?", text: "Change status?", icon: "warning",
            showCancelButton: true, confirmButtonColor: "#28a745", cancelButtonColor: "#d33",
            confirmButtonText: "Yes", cancelButtonText: "No"
        }).then((result) => {
            if (result.isConfirmed) {
                if (form.find("input[name='is_active']").length) {
                    form.find("input[name='is_active']").val(is_active);
                } else {
                    form.append(`<input type="hidden" name="is_active" value="${is_active}">`);
                }
                form.submit();
            } else {
                checkbox.prop("checked", !checkbox.is(":checked"));
            }
        });
    });
</script>
@endsection
