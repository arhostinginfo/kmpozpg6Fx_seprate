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
    /* Video card hover */
    .gallery-video-card:hover .play-btn-index {
        transform:scale(1.12);
        background:rgba(255,255,255,1) !important;
    }
    .play-btn-index { transition:transform .2s; }
</style>

<div class="page-container">
    <div class="container py-4">

        {{-- Page Header --}}
        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
            <div>
                <h2 class="fw-bold mb-1" style="color:var(--primary);">
                    <i class="fa fa-play-circle me-2"></i>
                    <span data-mr="चलतचित्र प्रदर्शनी" data-en="Video Gallery">चलतचित्र प्रदर्शनी</span>
                </h2>
                <p class="text-muted mb-0" style="font-size:0.9rem;">
                    <span data-mr="एकूण {{ $gallay_videos->total() }} व्हिडिओ" data-en="Total {{ $gallay_videos->total() }} Videos">
                        एकूण {{ $gallay_videos->total() }} व्हिडिओ
                    </span>
                </p>
            </div>
            <a href="{{ url('/') }}#video-gallary" class="btn btn-outline-secondary btn-sm">
                <i class="fa fa-arrow-left me-1"></i>
                <span data-mr="मागे जा" data-en="Go Back">मागे जा</span>
            </a>
        </div>

        @if($gallay_videos->count())

        {{-- Video Grid --}}
        <div class="row g-3">
            @foreach($gallay_videos as $video)
            <div class="col-12 col-sm-6 col-md-4 col-lg-4" data-aos="fade-up">
                <div class="gallery-video-card"
                     onclick="openVideoModal('{{ asset('storage/' . $video->attachment) }}', '{{ addslashes($video->name ?? '') }}')"
                     style="cursor:pointer; position:relative; border-radius:10px; overflow:hidden; border:1px solid var(--border,#e2e8f0); background:#000;">
                    <video src="{{ asset('storage/' . $video->attachment) }}"
                           style="width:100%; height:200px; object-fit:cover; display:block; pointer-events:none;"
                           preload="metadata">
                    </video>
                    <div class="play-overlay-index"
                         style="position:absolute; inset:0; display:flex; align-items:center; justify-content:center; background:rgba(0,0,0,.35);">
                        <div class="play-btn-index"
                             style="width:54px; height:54px; border-radius:50%; background:rgba(255,255,255,.92); display:flex; align-items:center; justify-content:center; box-shadow:0 2px 12px rgba(0,0,0,.3);">
                            <i class="fa fa-play" style="color:var(--primary); font-size:1.3rem; margin-left:4px;"></i>
                        </div>
                    </div>
                    @if($video->name)
                    <div style="position:absolute; bottom:0; left:0; right:0; background:linear-gradient(transparent,rgba(0,0,0,.65)); padding:8px 10px 6px; font-size:0.78rem; color:#fff; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                        {{ $video->name }}
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($gallay_videos->lastPage() > 1)
        <nav class="gp-pagination mt-4" aria-label="Video pagination">
            {{-- Previous --}}
            @if($gallay_videos->onFirstPage())
                <span class="page-item disabled">
                    <span class="page-link"><i class="fa fa-chevron-left"></i></span>
                </span>
            @else
                <a class="page-item" href="{{ $gallay_videos->previousPageUrl() }}">
                    <span class="page-link"><i class="fa fa-chevron-left"></i></span>
                </a>
            @endif

            {{-- Page Numbers --}}
            @php
                $current  = $gallay_videos->currentPage();
                $last     = $gallay_videos->lastPage();
                $start    = max(1, $current - 2);
                $end      = min($last, $current + 2);
            @endphp

            @if($start > 1)
                <a class="page-item" href="{{ $gallay_videos->url(1) }}"><span class="page-link">1</span></a>
                @if($start > 2)<span class="page-item disabled"><span class="page-link">…</span></span>@endif
            @endif

            @for($p = $start; $p <= $end; $p++)
                @if($p == $current)
                    <span class="page-item active"><span class="page-link">{{ $p }}</span></span>
                @else
                    <a class="page-item" href="{{ $gallay_videos->url($p) }}"><span class="page-link">{{ $p }}</span></a>
                @endif
            @endfor

            @if($end < $last)
                @if($end < $last - 1)<span class="page-item disabled"><span class="page-link">…</span></span>@endif
                <a class="page-item" href="{{ $gallay_videos->url($last) }}"><span class="page-link">{{ $last }}</span></a>
            @endif

            {{-- Next --}}
            @if($gallay_videos->hasMorePages())
                <a class="page-item" href="{{ $gallay_videos->nextPageUrl() }}">
                    <span class="page-link"><i class="fa fa-chevron-right"></i></span>
                </a>
            @else
                <span class="page-item disabled">
                    <span class="page-link"><i class="fa fa-chevron-right"></i></span>
                </span>
            @endif
        </nav>

        <p class="gp-page-info">
            पृष्ठ {{ $gallay_videos->currentPage() }} / {{ $gallay_videos->lastPage() }}
            &nbsp;·&nbsp;
            {{ $gallay_videos->firstItem() }}–{{ $gallay_videos->lastItem() }} पैकी {{ $gallay_videos->total() }} व्हिडिओ
        </p>
        @endif

        @else
        <div class="text-center py-5 text-muted">
            <i class="fa fa-play-circle fa-3x mb-3 d-block" style="color:var(--primary);opacity:.4;"></i>
            <span data-mr="कोणतेही व्हिडिओ उपलब्ध नाहीत" data-en="No videos available">कोणतेही व्हिडिओ उपलब्ध नाहीत</span>
        </div>
        @endif

    </div>
</div>

{{-- Video Modal --}}
<div class="modal fade" id="videoModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered gp-modal-dialog" style="max-width:560px;">
        <div class="modal-content">
            <div class="modal-header py-2 px-3" style="background:var(--primary, #006699);">
                <h6 class="modal-title fw-semibold mb-0" id="videoModalTitle"
                    style="color:#fff; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; max-width:calc(100% - 50px);"></h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0 d-flex align-items-center justify-content-center" style="background:#000; min-height:300px;">
                <video id="videoPlayer" controls style="width:100%; max-height:70vh; display:block;"></video>
            </div>
            <div class="modal-footer py-2 px-3 justify-content-end">
                <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="fa fa-times me-1"></i> बंद करा
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function openVideoModal(src, name) {
    var player = document.getElementById('videoPlayer');
    player.src = src;
    player.load();
    document.getElementById('videoModalTitle').textContent = name;
    new bootstrap.Modal(document.getElementById('videoModal')).show();
}
document.getElementById('videoModal').addEventListener('hide.bs.modal', function () {
    var player = document.getElementById('videoPlayer');
    player.pause();
    player.src = '';
});
</script>
@endsection
