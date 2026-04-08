<?php
namespace App\Http\Controllers\Superadm;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Officers;
use App\Models\Slider;
use App\Models\Marquees;
use App\Models\WelcomeNote;
use App\Models\Gallary;
use App\Models\Famouslocations;
use App\Models\Yojna;
use App\Models\Abhiyans;
use App\Models\PDFUpload;
use App\Models\TaxDemand;
use App\Models\TaxDocument;
use App\Models\TaxTip;
use App\Models\ContactDakhala;
use App\Models\Frontwebsitecontact;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index(Request $req)
    {
        try {
            // Gallery
            $totalPhotos  = Gallary::where('is_deleted', 0)->where('type_attachment', 'Image')->count();
            $activePhotos = Gallary::where('is_deleted', 0)->where('is_active', 1)->where('type_attachment', 'Image')->count();
            $totalVideos  = Gallary::where('is_deleted', 0)->where('type_attachment', 'Video')->count();
            $activeVideos = Gallary::where('is_deleted', 0)->where('is_active', 1)->where('type_attachment', 'Video')->count();

            // People
            $totalOfficers  = Officers::where('is_deleted', 0)->where('type', 'Officer')->count();
            $activeOfficers = Officers::where('is_deleted', 0)->where('is_active', 1)->where('type', 'Officer')->count();
            $totalSadsya    = Officers::where('is_deleted', 0)->where('type', 'Sadsya')->count();
            $activeSadsya   = Officers::where('is_deleted', 0)->where('is_active', 1)->where('type', 'Sadsya')->count();

            // Content
            $totalSlider   = Slider::where('is_deleted', 0)->count();
            $activeSlider  = Slider::where('is_deleted', 0)->where('is_active', 1)->count();
            $totalMarquee  = Marquees::where('is_deleted', 0)->count();
            $activeMarquee = Marquees::where('is_deleted', 0)->where('is_active', 1)->count();
            $totalWelcome  = WelcomeNote::where('is_deleted', 0)->count();
            $activeWelcome = WelcomeNote::where('is_deleted', 0)->where('is_active', 1)->count();

            // Places & Programs
            $totalLocations  = Famouslocations::where('is_deleted', 0)->count();
            $activeLocations = Famouslocations::where('is_deleted', 0)->where('is_active', 1)->count();
            $totalYojna      = Yojna::where('is_deleted', 0)->count();
            $activeYojna     = Yojna::where('is_deleted', 0)->where('is_active', 1)->count();
            $totalAbhiyan    = Abhiyans::where('is_deleted', 0)->count();
            $activeAbhiyan   = Abhiyans::where('is_deleted', 0)->where('is_active', 1)->count();
            $totalPdf        = PDFUpload::where('is_deleted', 0)->count();
            $activePdf       = PDFUpload::where('is_deleted', 0)->where('is_active', 1)->count();

            // Tax
            $totalTaxDemand    = TaxDemand::where('is_deleted', 0)->count();
            $activeTaxDemand   = TaxDemand::where('is_deleted', 0)->where('is_active', 1)->count();
            $totalTaxDocument  = TaxDocument::where('is_deleted', 0)->count();
            $activeTaxDocument = TaxDocument::where('is_deleted', 0)->where('is_active', 1)->count();
            $totalTaxTip       = TaxTip::where('is_deleted', 0)->count();
            $activeTaxTip      = TaxTip::where('is_deleted', 0)->where('is_active', 1)->count();

            // Requests
            $totalDakhala    = ContactDakhala::where('is_deleted', 0)->count();
            $pendingDakhala  = ContactDakhala::where('is_deleted', 0)->where('is_action_completed', 0)->count();
            $totalContact    = Frontwebsitecontact::where('is_deleted', 0)->count();
            $pendingContact  = Frontwebsitecontact::where('is_deleted', 0)->where('is_active', 0)->count();

            // Recent dakhala requests
            $recentDakhala = ContactDakhala::where('is_deleted', 0)
                ->latest()->take(5)->get();

            // Recent contact messages
            $recentContact = Frontwebsitecontact::where('is_deleted', 0)
                ->latest()->take(5)->get();

            return view('dashboard.dashboard', compact(
                'totalPhotos',  'activePhotos',
                'totalVideos',  'activeVideos',
                'totalOfficers','activeOfficers',
                'totalSadsya',  'activeSadsya',
                'totalSlider',  'activeSlider',
                'totalMarquee', 'activeMarquee',
                'totalWelcome', 'activeWelcome',
                'totalLocations','activeLocations',
                'totalYojna',   'activeYojna',
                'totalAbhiyan', 'activeAbhiyan',
                'totalPdf',     'activePdf',
                'totalTaxDemand',   'activeTaxDemand',
                'totalTaxDocument', 'activeTaxDocument',
                'totalTaxTip',      'activeTaxTip',
                'totalDakhala', 'pendingDakhala',
                'totalContact', 'pendingContact',
                'recentDakhala','recentContact'
            ));

        } catch (\Exception $e) {
            Log::error('DashboardController@index error: ' . $e->getMessage());
            return back()->with('error', 'Something went wrong while loading the dashboard.');
        }
    }
}
