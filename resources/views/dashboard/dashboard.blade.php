@extends('superadm.layout.master')

@section('title', 'Dashboard')

@section('content')
<style>
    .dash-card {
        border-radius: 12px;
        border: none;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        transition: transform .18s, box-shadow .18s;
        text-decoration: none;
        display: block;
        color: inherit;
    }
    .dash-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 6px 24px rgba(0,0,0,0.13);
        color: inherit;
    }
    .dash-icon {
        width: 54px; height: 54px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.5rem;
        flex-shrink: 0;
    }
    .dash-count { font-size: 1.9rem; font-weight: 700; line-height: 1; }
    .dash-label { font-size: 0.82rem; color: #6c757d; margin-top: 2px; }
    .dash-badge {
        font-size: 0.72rem; padding: 2px 8px;
        border-radius: 20px; font-weight: 500;
    }
    .section-heading {
        font-size: 0.75rem; font-weight: 700; letter-spacing: .08em;
        text-transform: uppercase; color: #6c757d;
        border-bottom: 2px solid #f0f0f0;
        padding-bottom: 6px; margin-bottom: 14px; margin-top: 8px;
    }
    .recent-table td, .recent-table th { font-size: 0.82rem; vertical-align: middle; }
    .status-dot {
        width: 8px; height: 8px; border-radius: 50%; display: inline-block; margin-right: 5px;
    }
</style>

<div class="row">
  <div class="col-12">
    <div class="card" style="border:none !important; box-shadow:none !important; background:transparent !important;">
      <div class="card-body" style="padding-left:0 !important; padding-right:0 !important; padding-top:8px !important;">

        <div class="d-flex align-items-center justify-content-between mb-3">
            <h4 class="mb-0" style="font-weight:700;">Dashboard</h4>
            <small class="text-muted">{{ now()->format('d M Y, h:i A') }}</small>
        </div>

        {{-- ══════════════════════════════════════════
             ROW 1 — REQUESTS (highlighted, top priority)
        ══════════════════════════════════════════ --}}
        <div class="section-heading">📥 User Requests</div>
        <div class="row g-3 mb-4">

            {{-- Dakhala --}}
            <div class="col-6 col-sm-4 col-md-3 col-lg-3">
                <a href="{{ route('dakhala.list') }}" class="dash-card card">
                    <div class="card-body d-flex align-items-center gap-3 py-3">
                        <div class="dash-icon" style="background:#fff3cd;">
                            <i class="mdi mdi-file-document text-warning"></i>
                        </div>
                        <div>
                            <div class="dash-count">{{ $totalDakhala }}</div>
                            <div class="dash-label">दाखला अर्ज</div>
                            @if($pendingDakhala > 0)
                            <span class="dash-badge bg-danger text-white mt-1 d-inline-block">
                                {{ $pendingDakhala }} Pending
                            </span>
                            @else
                            <span class="dash-badge bg-success text-white mt-1 d-inline-block">All Done</span>
                            @endif
                        </div>
                    </div>
                </a>
            </div>

            {{-- Contact Messages --}}
            <div class="col-6 col-sm-4 col-md-3 col-lg-3">
                <a href="{{ route('contact.list') }}" class="dash-card card">
                    <div class="card-body d-flex align-items-center gap-3 py-3">
                        <div class="dash-icon" style="background:#d1ecf1;">
                            <i class="mdi mdi-email text-info"></i>
                        </div>
                        <div>
                            <div class="dash-count">{{ $totalContact }}</div>
                            <div class="dash-label">संपर्क संदेश</div>
                            @if($pendingContact > 0)
                            <span class="dash-badge bg-warning text-dark mt-1 d-inline-block">
                                {{ $pendingContact }} Unread
                            </span>
                            @else
                            <span class="dash-badge bg-success text-white mt-1 d-inline-block">All Read</span>
                            @endif
                        </div>
                    </div>
                </a>
            </div>

        </div>

        {{-- ══════════════════════════════════════════
             ROW 2 — GALLERY
        ══════════════════════════════════════════ --}}
        <div class="section-heading">🖼️ Gallery</div>
        <div class="row g-3 mb-4">

            <div class="col-6 col-sm-4 col-md-3 col-lg-3">
                <a href="{{ route('gallary.list') }}" class="dash-card card">
                    <div class="card-body d-flex align-items-center gap-3 py-3">
                        <div class="dash-icon" style="background:#e3f2fd;">
                            <i class="mdi mdi-image-multiple" style="color:#1565c0;"></i>
                        </div>
                        <div>
                            <div class="dash-count">{{ $totalPhotos }}</div>
                            <div class="dash-label">छायाचित्रे</div>
                            <span class="dash-badge mt-1 d-inline-block"
                                  style="background:#e3f2fd; color:#1565c0;">
                                {{ $activePhotos }} Active
                            </span>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-6 col-sm-4 col-md-3 col-lg-3">
                <a href="{{ route('gallary.list') }}" class="dash-card card">
                    <div class="card-body d-flex align-items-center gap-3 py-3">
                        <div class="dash-icon" style="background:#fce4ec;">
                            <i class="mdi mdi-video" style="color:#c62828;"></i>
                        </div>
                        <div>
                            <div class="dash-count">{{ $totalVideos }}</div>
                            <div class="dash-label">व्हिडिओ</div>
                            <span class="dash-badge mt-1 d-inline-block"
                                  style="background:#fce4ec; color:#c62828;">
                                {{ $activeVideos }} Active
                            </span>
                        </div>
                    </div>
                </a>
            </div>

        </div>

        {{-- ══════════════════════════════════════════
             ROW 3 — PEOPLE
        ══════════════════════════════════════════ --}}
        <div class="section-heading">👥 People</div>
        <div class="row g-3 mb-4">

            <div class="col-6 col-sm-4 col-md-3 col-lg-3">
                <a href="{{ route('officers.list') }}" class="dash-card card">
                    <div class="card-body d-flex align-items-center gap-3 py-3">
                        <div class="dash-icon" style="background:#e8f5e9;">
                            <i class="mdi mdi-account-tie" style="color:#2e7d32;"></i>
                        </div>
                        <div>
                            <div class="dash-count">{{ $totalOfficers }}</div>
                            <div class="dash-label">अधिकारी / कर्मचारी</div>
                            <span class="dash-badge mt-1 d-inline-block"
                                  style="background:#e8f5e9; color:#2e7d32;">
                                {{ $activeOfficers }} Active
                            </span>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-6 col-sm-4 col-md-3 col-lg-3">
                <a href="{{ route('officers.list') }}" class="dash-card card">
                    <div class="card-body d-flex align-items-center gap-3 py-3">
                        <div class="dash-icon" style="background:#f3e5f5;">
                            <i class="mdi mdi-account-group" style="color:#6a1b9a;"></i>
                        </div>
                        <div>
                            <div class="dash-count">{{ $totalSadsya }}</div>
                            <div class="dash-label">सदस्य</div>
                            <span class="dash-badge mt-1 d-inline-block"
                                  style="background:#f3e5f5; color:#6a1b9a;">
                                {{ $activeSadsya }} Active
                            </span>
                        </div>
                    </div>
                </a>
            </div>

        </div>

        {{-- ══════════════════════════════════════════
             ROW 4 — CONTENT
        ══════════════════════════════════════════ --}}
        <div class="section-heading">📝 Content</div>
        <div class="row g-3 mb-4">

            <div class="col-6 col-sm-4 col-md-3 col-lg-3">
                <a href="{{ route('slider.list') }}" class="dash-card card">
                    <div class="card-body d-flex align-items-center gap-3 py-3">
                        <div class="dash-icon" style="background:#e0f2f1;">
                            <i class="mdi mdi-view-carousel" style="color:#00695c;"></i>
                        </div>
                        <div>
                            <div class="dash-count">{{ $totalSlider }}</div>
                            <div class="dash-label">Slider</div>
                            <span class="dash-badge mt-1 d-inline-block"
                                  style="background:#e0f2f1; color:#00695c;">
                                {{ $activeSlider }} Active
                            </span>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-6 col-sm-4 col-md-3 col-lg-3">
                <a href="{{ route('marquee.list') }}" class="dash-card card">
                    <div class="card-body d-flex align-items-center gap-3 py-3">
                        <div class="dash-icon" style="background:#fff8e1;">
                            <i class="mdi mdi-bullhorn" style="color:#f57f17;"></i>
                        </div>
                        <div>
                            <div class="dash-count">{{ $totalMarquee }}</div>
                            <div class="dash-label">Marquee Notice</div>
                            <span class="dash-badge mt-1 d-inline-block"
                                  style="background:#fff8e1; color:#f57f17;">
                                {{ $activeMarquee }} Active
                            </span>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-6 col-sm-4 col-md-3 col-lg-3">
                <a href="{{ route('welcome-note.list') }}" class="dash-card card">
                    <div class="card-body d-flex align-items-center gap-3 py-3">
                        <div class="dash-icon" style="background:#e8eaf6;">
                            <i class="mdi mdi-hand-wave" style="color:#283593;"></i>
                        </div>
                        <div>
                            <div class="dash-count">{{ $totalWelcome }}</div>
                            <div class="dash-label">Welcome Note</div>
                            <span class="dash-badge mt-1 d-inline-block"
                                  style="background:#e8eaf6; color:#283593;">
                                {{ $activeWelcome }} Active
                            </span>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-6 col-sm-4 col-md-3 col-lg-3">
                <a href="{{ route('pdfupload.list') }}" class="dash-card card">
                    <div class="card-body d-flex align-items-center gap-3 py-3">
                        <div class="dash-icon" style="background:#fbe9e7;">
                            <i class="mdi mdi-file-pdf-box" style="color:#bf360c;"></i>
                        </div>
                        <div>
                            <div class="dash-count">{{ $totalPdf }}</div>
                            <div class="dash-label">PDF Documents</div>
                            <span class="dash-badge mt-1 d-inline-block"
                                  style="background:#fbe9e7; color:#bf360c;">
                                {{ $activePdf }} Active
                            </span>
                        </div>
                    </div>
                </a>
            </div>

        </div>

        {{-- ══════════════════════════════════════════
             ROW 5 — PLACES & PROGRAMS
        ══════════════════════════════════════════ --}}
        <div class="section-heading">📍 Places & Programs</div>
        <div class="row g-3 mb-4">

            <div class="col-6 col-sm-4 col-md-3 col-lg-3">
                <a href="{{ route('famous-locations.list') }}" class="dash-card card">
                    <div class="card-body d-flex align-items-center gap-3 py-3">
                        <div class="dash-icon" style="background:#e8f5e9;">
                            <i class="mdi mdi-map-marker" style="color:#388e3c;"></i>
                        </div>
                        <div>
                            <div class="dash-count">{{ $totalLocations }}</div>
                            <div class="dash-label">प्रसिद्ध स्थळे</div>
                            <span class="dash-badge mt-1 d-inline-block"
                                  style="background:#e8f5e9; color:#388e3c;">
                                {{ $activeLocations }} Active
                            </span>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-6 col-sm-4 col-md-3 col-lg-3">
                <a href="{{ route('yojna.list') }}" class="dash-card card">
                    <div class="card-body d-flex align-items-center gap-3 py-3">
                        <div class="dash-icon" style="background:#e3f2fd;">
                            <i class="mdi mdi-clipboard-list" style="color:#1976d2;"></i>
                        </div>
                        <div>
                            <div class="dash-count">{{ $totalYojna }}</div>
                            <div class="dash-label">शासकीय योजना</div>
                            <span class="dash-badge mt-1 d-inline-block"
                                  style="background:#e3f2fd; color:#1976d2;">
                                {{ $activeYojna }} Active
                            </span>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-6 col-sm-4 col-md-3 col-lg-3">
                <a href="{{ route('abhiyan.list') }}" class="dash-card card">
                    <div class="card-body d-flex align-items-center gap-3 py-3">
                        <div class="dash-icon" style="background:#fff3e0;">
                            <i class="mdi mdi-flag" style="color:#e65100;"></i>
                        </div>
                        <div>
                            <div class="dash-count">{{ $totalAbhiyan }}</div>
                            <div class="dash-label">अभियान</div>
                            <span class="dash-badge mt-1 d-inline-block"
                                  style="background:#fff3e0; color:#e65100;">
                                {{ $activeAbhiyan }} Active
                            </span>
                        </div>
                    </div>
                </a>
            </div>

        </div>

        {{-- ══════════════════════════════════════════
             ROW 6 — TAX
        ══════════════════════════════════════════ --}}
        <div class="section-heading">💰 Tax Management</div>
        <div class="row g-3 mb-4">

            <div class="col-6 col-sm-4 col-md-3 col-lg-3">
                <a href="{{ route('tax-demand.list') }}" class="dash-card card">
                    <div class="card-body d-flex align-items-center gap-3 py-3">
                        <div class="dash-icon" style="background:#e8f5e9;">
                            <i class="mdi mdi-currency-inr" style="color:#2e7d32;"></i>
                        </div>
                        <div>
                            <div class="dash-count">{{ $totalTaxDemand }}</div>
                            <div class="dash-label">कर मागणी नोंदी</div>
                            <span class="dash-badge mt-1 d-inline-block"
                                  style="background:#e8f5e9; color:#2e7d32;">
                                {{ $activeTaxDemand }} Active
                            </span>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-6 col-sm-4 col-md-3 col-lg-3">
                <a href="{{ route('tax-document.list') }}" class="dash-card card">
                    <div class="card-body d-flex align-items-center gap-3 py-3">
                        <div class="dash-icon" style="background:#fce4ec;">
                            <i class="mdi mdi-qrcode" style="color:#880e4f;"></i>
                        </div>
                        <div>
                            <div class="dash-count">{{ $totalTaxDocument }}</div>
                            <div class="dash-label">कर दस्तऐवज (PDF/QR)</div>
                            <span class="dash-badge mt-1 d-inline-block"
                                  style="background:#fce4ec; color:#880e4f;">
                                {{ $activeTaxDocument }} Active
                            </span>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-6 col-sm-4 col-md-3 col-lg-3">
                <a href="{{ route('tax-tip.list') }}" class="dash-card card">
                    <div class="card-body d-flex align-items-center gap-3 py-3">
                        <div class="dash-icon" style="background:#fff3e0;">
                            <i class="mdi mdi-lightbulb-on" style="color:#e65100;"></i>
                        </div>
                        <div>
                            <div class="dash-count">{{ $totalTaxTip }}</div>
                            <div class="dash-label">कर टीप</div>
                            <span class="dash-badge mt-1 d-inline-block"
                                  style="background:#fff3e0; color:#e65100;">
                                {{ $activeTaxTip }} Active
                            </span>
                        </div>
                    </div>
                </a>
            </div>

        </div>

        {{-- ══════════════════════════════════════════
             ROW 7 — RECENT ACTIVITY TABLES
        ══════════════════════════════════════════ --}}
        <div class="section-heading">🕐 Recent Activity</div>
        <div class="row g-3">

            {{-- Recent Dakhala --}}
            <div class="col-12 col-lg-6">
                <div class="card" style="border-radius:10px; border:1px solid #f0f0f0;">
                    <div class="card-header d-flex justify-content-between align-items-center py-2 px-3"
                         style="background:#fff8e1; border-bottom:1px solid #f0e0a0; border-radius:10px 10px 0 0;">
                        <span style="font-size:0.85rem; font-weight:600; color:#795548;">
                            <i class="mdi mdi-file-document me-1"></i> अलीकडील दाखला अर्ज
                        </span>
                        <a href="{{ route('dakhala.list') }}" class="btn btn-xs btn-outline-secondary"
                           style="font-size:0.72rem; padding:2px 10px;">सर्व पहा</a>
                    </div>
                    <div class="card-body p-0">
                        @if($recentDakhala->count())
                        <table class="table table-sm table-hover mb-0 recent-table">
                            <thead style="background:#fafafa;">
                                <tr>
                                    <th class="ps-3">नाव</th>
                                    <th>प्रकार</th>
                                    <th>स्थिती</th>
                                    <th>दिनांक</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentDakhala as $d)
                                <tr>
                                    <td class="ps-3">{{ $d->applicant_name }}</td>
                                    <td>{{ $d->certificate_type }}</td>
                                    <td>
                                        @if($d->is_action_completed)
                                            <span class="badge bg-success" style="font-size:.68rem;">Done</span>
                                        @else
                                            <span class="badge bg-warning text-dark" style="font-size:.68rem;">Pending</span>
                                        @endif
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($d->created_at)->format('d M') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                        <div class="text-center text-muted py-4" style="font-size:0.82rem;">
                            <i class="mdi mdi-inbox-outline d-block mb-1" style="font-size:1.5rem;"></i>
                            No requests yet
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Recent Contact Messages --}}
            <div class="col-12 col-lg-6">
                <div class="card" style="border-radius:10px; border:1px solid #f0f0f0;">
                    <div class="card-header d-flex justify-content-between align-items-center py-2 px-3"
                         style="background:#e3f2fd; border-bottom:1px solid #bbdefb; border-radius:10px 10px 0 0;">
                        <span style="font-size:0.85rem; font-weight:600; color:#1565c0;">
                            <i class="mdi mdi-email me-1"></i> अलीकडील संपर्क संदेश
                        </span>
                        <a href="{{ route('contact.list') }}" class="btn btn-xs btn-outline-secondary"
                           style="font-size:0.72rem; padding:2px 10px;">सर्व पहा</a>
                    </div>
                    <div class="card-body p-0">
                        @if($recentContact->count())
                        <table class="table table-sm table-hover mb-0 recent-table">
                            <thead style="background:#fafafa;">
                                <tr>
                                    <th class="ps-3">नाव</th>
                                    <th>मोबाईल</th>
                                    <th>संदेश</th>
                                    <th>दिनांक</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentContact as $c)
                                <tr>
                                    <td class="ps-3">{{ $c->name }}</td>
                                    <td>{{ $c->mobile_no }}</td>
                                    <td style="max-width:140px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                                        {{ $c->message }}
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($c->created_at)->format('d M') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                        <div class="text-center text-muted py-4" style="font-size:0.82rem;">
                            <i class="mdi mdi-email-outline d-block mb-1" style="font-size:1.5rem;"></i>
                            No messages yet
                        </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>{{-- end recent row --}}

      </div>{{-- card-body --}}
    </div>{{-- card --}}
  </div>
</div>
@endsection
