@extends('superadm.layout.master')

@section('title', 'Tax Document')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <div class="mb-3 d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">कर माहिती व भरणा (PDF / QR)</h4>
                    <a href="{{ route('tax-document.create') }}" class="btn btn-sm btn-outline-primary">+ दस्तऐवज अपलोड करा</a>
                </div>

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <div class="table-responsive">
                    <table class="table table-bordered table-striped datatables">
                        <thead>
                            <tr>
                                <th>अ.नं.</th>
                                <th>कराचा प्रकार</th>
                                <th>दस्तऐवज प्रकार</th>
                                <th>फाईल नाव</th>
                                <th>Preview</th>
                                <th>स्थिती</th>
                                <th>कृती</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($documents as $key => $doc)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>
                                        @if($doc->tax_type === 'ghar_patti')
                                            <span class="badge bg-primary">घरपट्टी कर</span>
                                        @elseif($doc->tax_type === 'paani_patti')
                                            <span class="badge bg-info">पाणीपट्टी कर</span>
                                        @else
                                            <span class="badge bg-warning text-dark">गाळाभाडे / व्यवसायकर / इतर</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($doc->document_type === 'view_pdf')
                                            <span class="badge bg-danger">PDF पहा</span>
                                        @else
                                            <span class="badge bg-success">QR पेमेंट</span>
                                        @endif
                                    </td>
                                    <td style="font-size:0.82rem;">{{ $doc->original_name ?? basename($doc->file_path) }}</td>
                                    <td>
                                        @if($doc->isImage())
                                            <img src="{{ asset('storage/' . $doc->file_path) }}"
                                                class="img-thumb"
                                                onclick="openImgModal('{{ asset('storage/' . $doc->file_path) }}')"
                                                title="Click to preview">
                                        @else
                                            <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank"
                                                class="btn btn-sm btn-outline-danger">
                                                <i class="mdi mdi-file-pdf-box"></i> PDF
                                            </a>
                                        @endif
                                    </td>
                                    <td>
                                        <form action="{{ route('tax-document.updatestatus') }}" method="POST" class="d-inline-block">
                                            @csrf
                                            <label class="switch">
                                                <input type="checkbox" class="toggle-status"
                                                    data-id="{{ base64_encode($doc->id) }}"
                                                    {{ $doc->is_active == 1 ? 'checked' : '' }}>
                                                <span class="slider"></span>
                                            </label>
                                            <input type="hidden" name="id" value="{{ base64_encode($doc->id) }}">
                                        </form>
                                    </td>
                                    <td>
                                        <a href="{{ route('tax-document.edit', base64_encode($doc->id)) }}"
                                            class="btn btn-sm btn-outline-primary">Edit</a>
                                        <form action="{{ route('tax-document.delete') }}" method="POST" class="d-inline-block">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ base64_encode($doc->id) }}">
                                            <button type="button" class="btn btn-sm btn-outline-danger delete-btn">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
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
