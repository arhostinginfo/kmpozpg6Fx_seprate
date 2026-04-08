@extends('website.layout.header')

@section('content')
<style>
    /* Pagination styling */
    .gp-pagination { display:flex; align-items:center; justify-content:center; flex-wrap:wrap; gap:6px; margin-top:32px; }
    .gp-pagination .page-item .page-link {
        border-radius:8px !important;
        border:1px solid var(--border, #e2e8f0);
        color:var(--primary, #006699);
        font-size:0.88rem;
        padding:6px 14px;
        min-width:38px;
        text-align:center;
        transition:all .2s;
        background:var(--card, #fff);
    }
    .gp-pagination .page-item .page-link:hover {
        background:var(--primary, #006699);
        color:#fff;
        border-color:var(--primary, #006699);
    }
    .gp-pagination .page-item.active .page-link {
        background:var(--primary, #006699);
        color:#fff;
        border-color:var(--primary, #006699);
        font-weight:600;
        box-shadow:0 2px 8px rgba(0,102,153,.3);
    }
    .gp-pagination .page-item.disabled .page-link {
        color:#adb5bd;
        background:var(--card, #fff);
        border-color:var(--border, #e2e8f0);
        cursor:not-allowed;
    }
    .gp-page-info {
        text-align:center;
        font-size:0.82rem;
        color:#6c757d;
        margin-top:10px;
    }
    /* Hover lift on photo card */
    .gallery-photo-card img:hover { transform:scale(1.04); }
</style>

<div class="page-container">
    <div class="container py-4">

        {{-- Page Header --}}
        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
            <div>
                <h2 class="fw-bold mb-1" style="color:var(--primary);">
                    <i class="fa fa-images me-2"></i>
                    <span data-mr="छायाचित्र प्रदर्शनी" data-en="Photo Gallery">छायाचित्र प्रदर्शनी</span>
                </h2>
                <p class="text-muted mb-0" style="font-size:0.9rem;">
                    <span data-mr="एकूण {{ $gallay_photos->total() }} छायाचित्रे" data-en="Total {{ $gallay_photos->total() }} Photos">
                        एकूण {{ $gallay_photos->total() }} छायाचित्रे
                    </span>
                </p>
            </div>
            <a href="{{ url('/') }}#photo-gallary" class="btn btn-outline-secondary btn-sm">
                <i class="fa fa-arrow-left me-1"></i>
                <span data-mr="मागे जा" data-en="Go Back">मागे जा</span>
            </a>
        </div>

        @if($gallay_photos->count())

        {{-- Photo Grid --}}
        <div class="row g-3">
            @foreach($gallay_photos as $photo)
            <div class="col-6 col-sm-4 col-md-3 col-lg-3" data-aos="fade-up">
                <div class="gallery-photo-card"
                     data-bs-toggle="modal" data-bs-target="#photoModal"
                     data-bs-src="{{ asset('storage/' . $photo->attachment) }}"
                     data-bs-name="{{ $photo->name ?? '' }}"
                     style="cursor:pointer;">
                    <img src="{{ asset('storage/' . $photo->attachment) }}"
                         alt="{{ $photo->name ?? 'Photo' }}"
                         loading="lazy"
                         style="width:100%; height:180px; object-fit:cover; border-radius:8px; border:1px solid var(--border, #e2e8f0); transition:transform .2s;">
                    @if($photo->name)
                    <div class="mt-1 text-center" style="font-size:0.78rem; color:var(--text,#555); white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                        {{ $photo->name }}
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($gallay_photos->lastPage() > 1)
        <nav class="gp-pagination mt-4" aria-label="Photo pagination">
            {{-- Previous --}}
            @if($gallay_photos->onFirstPage())
                <span class="page-item disabled">
                    <span class="page-link"><i class="fa fa-chevron-left"></i></span>
                </span>
            @else
                <a class="page-item" href="{{ $gallay_photos->previousPageUrl() }}">
                    <span class="page-link"><i class="fa fa-chevron-left"></i></span>
                </a>
            @endif

            {{-- Page Numbers --}}
            @php
                $current  = $gallay_photos->currentPage();
                $last     = $gallay_photos->lastPage();
                $start    = max(1, $current - 2);
                $end      = min($last, $current + 2);
            @endphp

            @if($start > 1)
                <a class="page-item" href="{{ $gallay_photos->url(1) }}"><span class="page-link">1</span></a>
                @if($start > 2)<span class="page-item disabled"><span class="page-link">…</span></span>@endif
            @endif

            @for($p = $start; $p <= $end; $p++)
                @if($p == $current)
                    <span class="page-item active"><span class="page-link">{{ $p }}</span></span>
                @else
                    <a class="page-item" href="{{ $gallay_photos->url($p) }}"><span class="page-link">{{ $p }}</span></a>
                @endif
            @endfor

            @if($end < $last)
                @if($end < $last - 1)<span class="page-item disabled"><span class="page-link">…</span></span>@endif
                <a class="page-item" href="{{ $gallay_photos->url($last) }}"><span class="page-link">{{ $last }}</span></a>
            @endif

            {{-- Next --}}
            @if($gallay_photos->hasMorePages())
                <a class="page-item" href="{{ $gallay_photos->nextPageUrl() }}">
                    <span class="page-link"><i class="fa fa-chevron-right"></i></span>
                </a>
            @else
                <span class="page-item disabled">
                    <span class="page-link"><i class="fa fa-chevron-right"></i></span>
                </span>
            @endif
        </nav>

        <p class="gp-page-info">
            पृष्ठ {{ $gallay_photos->currentPage() }} / {{ $gallay_photos->lastPage() }}
            &nbsp;·&nbsp;
            {{ $gallay_photos->firstItem() }}–{{ $gallay_photos->lastItem() }} पैकी {{ $gallay_photos->total() }} छायाचित्रे
        </p>
        @endif

        @else
        <div class="text-center py-5 text-muted">
            <i class="fa fa-images fa-3x mb-3 d-block" style="color:var(--primary);opacity:.4;"></i>
            <span data-mr="कोणतीही छायाचित्रे उपलब्ध नाहीत" data-en="No photos available">कोणतीही छायाचित्रे उपलब्ध नाहीत</span>
        </div>
        @endif

    </div>
</div>

{{-- Photo Modal --}}
<div class="modal fade" id="photoModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered gp-modal-dialog">
        <div class="modal-content">
            <div class="modal-header py-2 px-3" style="background:var(--primary, #006699);">
                <h6 class="modal-title fw-semibold mb-0" id="photoModalTitle"
                    style="color:#fff; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; max-width:calc(100% - 50px);"></h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0 d-flex align-items-center justify-content-center" style="background:#000; min-height:300px;">
                <img id="photoModalImg" src="" style="max-width:100%; max-height:70vh; object-fit:contain; display:block;">
            </div>
            <div class="modal-footer py-2 px-3 justify-content-between">
                <small id="photoModalCaption" class="text-muted"
                    style="font-size:0.82rem; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; max-width:70%;"></small>
                <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="fa fa-times me-1"></i> बंद करा
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.gallery-photo-card[data-bs-toggle="modal"]').forEach(function(card) {
    card.addEventListener('click', function() {
        var src  = this.dataset.bsSrc;
        var name = this.dataset.bsName || '';
        document.getElementById('photoModalImg').src              = src;
        document.getElementById('photoModalTitle').textContent    = name;
        document.getElementById('photoModalCaption').textContent  = name;
    });
});
</script>
@endsection
