<?php

namespace App\Http\Controllers;

use App\Models\
{
    WelcomeNote,
    Officers,
    Navbars,
    Slider,
    Marquees,
    Famouslocations,
    Yojna,
    Abhiyans,
    Gallary,
    PDFUpload,
    ContactDakhala,
    TaxDemand,
    TaxDocument,
    TaxTip

};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WebSiteController extends Controller
{
    public function index()
    {

        $welcomenote = WelcomeNote::where('is_deleted', 0)
                ->where('is_active', 1)
                ->orderBy('id', 'desc')
                ->first();

        // $officers = Officers::where('is_deleted',0)
        //         ->where('is_active', 1)
        //         ->orderBy('id', 'desc')
        //         ->get();

        $officerData =  Officers::where('is_deleted',0)
                ->where('is_active', 1)
                ->where('type', '=',  'Officer')
                ->orderBy('sequence_officer', 'asc')
                ->get();
        $sadsyaAll  =Officers::where('is_deleted',0)
                ->where('is_active', 1)
                ->where('type', '=',  'Sadsya')
                ->orderBy('sequence_general', 'asc')
                ->get();



        $gallay_photos = Gallary::where('is_deleted', 0)
                ->where('is_active', 1)
                ->where('type_attachment', 'Image')
                ->latest()
                ->get();

        $gallay_videos = Gallary::where('is_deleted', 0)
                ->where('is_active', 1)
                ->where('type_attachment', 'Video')
                ->latest()
                ->get();


        $navbar =  Navbars::where('is_deleted',0)
                ->where('is_active', 1)
                ->orderBy('id', 'desc')
                ->first();

        $slider = Slider::where('is_deleted',0)
                ->where('is_active', 1)        
                ->orderBy('id', 'desc')
                ->get();

       $marquee = Marquees::where('is_deleted', 0)
                    ->where('is_active', 1)       
                    ->orderBy('id', 'asc')
                    ->pluck('message')   // get only the message column
                    ->implode(' | ');    // join with |

        $yojna_all = Yojna::where('is_deleted',0)
                ->where('is_active', 1)
                ->orderBy('id', 'desc')
                ->get();


        $AbhiyanAll = Abhiyans::where('is_deleted',0)
                ->where('is_active', 1)        
                ->orderBy('id', 'desc')
                ->get();

         $famouslocations = Famouslocations::where('is_deleted',0)
                ->where('is_active', 1)
                ->orderBy('id', 'desc')
                ->get();  
                
         $pdf_all = PDFUpload::where([
					'is_deleted'=>0,
				])
                ->where('is_active', 1)
                ->orderBy('id', 'desc')
                ->get();

        $taxDemands = TaxDemand::where('is_deleted', 0)
                ->where('is_active', 1)
                ->orderByRaw("FIELD(tax_type,'ghar_patti','paani_patti','other')")
                ->orderByRaw("FIELD(year_type,'chalu','magil')")
                ->get();

        // View uses $gharPattiDemands['magil'] and $gharPattiDemands['chalu'] — key by year_type
        $gharPattiDemands  = $taxDemands->where('tax_type', 'ghar_patti')->keyBy('year_type');
        $paaniPattiDemands = $taxDemands->where('tax_type', 'paani_patti')->keyBy('year_type');
        $otherDemands      = $taxDemands->where('tax_type', 'other')->keyBy('year_type');

        // View uses $taxDocuments['ghar_patti']['view_pdf'][0] — nest as [tax_type][document_type][index]
        $taxDocumentsRaw = TaxDocument::where('is_deleted', 0)
                ->where('is_active', 1)
                ->orderByRaw("FIELD(tax_type,'ghar_patti','paani_patti','other')")
                ->orderByRaw("FIELD(document_type,'view_pdf','payment_qr')")
                ->get();

        $taxDocuments = [];
        foreach ($taxDocumentsRaw as $doc) {
            $taxDocuments[$doc->tax_type][$doc->document_type][] = $doc;
        }

        // Latest active tax tip
        $taxTip = TaxTip::where('is_deleted', 0)
                ->where('is_active', 1)
                ->latest()
                ->first();

        return view('website.index', compact(
            'welcomenote', 'gallay_photos', 'gallay_videos', 'navbar', 'slider', 'marquee',
            'famouslocations', 'AbhiyanAll', 'yojna_all', 'officerData', 'sadsyaAll', 'pdf_all',
            'gharPattiDemands', 'paaniPattiDemands', 'otherDemands', 'taxDocuments', 'taxTip'
        ));
    }


    public function dakhalaStore(Request $request)
    {
        $data = $request->validate([
            'mobile_no'        => ['required', 'regex:/^[6-9]\d{9}$/'],
            'applicant_name'   => 'required|string|max:255',
            'applicant_email'   => 'required|email|max:255',
            'print_name'       => 'required|string|max:255',
            'address'          => 'required|string',
            'certificate_type' => 'required|string',
        ]);

        try {
            ContactDakhala::create($data);

            return redirect()->back()
                ->with('dakhala_success', 'आपला अर्ज यशस्वीरित्या सबमिट झाला आहे.')
                ->withFragment('dakhala');

        } catch (\Exception $e) {

            \Log::error($e);

            return redirect()->back()
                ->with('dakhala_error', 'काहीतरी चूक झाली. कृपया पुन्हा प्रयत्न करा.')
                ->withFragment('dakhala');
        }
    }


    public function galleryPhotos()
    {
        $gallay_photos = Gallary::where('is_deleted', 0)
                ->where('is_active', 1)
                ->where('type_attachment', 'Image')
                ->latest()
                ->paginate(12);

        $navbar = Navbars::where('is_deleted', 0)
                ->where('is_active', 1)
                ->orderBy('id', 'desc')
                ->first();

        return view('website.gallery.photos', compact('gallay_photos', 'navbar'));
    }

    public function galleryVideos()
    {
        $gallay_videos = Gallary::where('is_deleted', 0)
                ->where('is_active', 1)
                ->where('type_attachment', 'Video')
                ->latest()
                ->paginate(9);

        $navbar = Navbars::where('is_deleted', 0)
                ->where('is_active', 1)
                ->orderBy('id', 'desc')
                ->first();

        return view('website.gallery.videos', compact('gallay_videos', 'navbar'));
    }

        public function contactStore(Request $request)
    {
        $data = $request->validate([
            'mobile_no'        => ['required', 'regex:/^[6-9]\d{9}$/'],
            'applicant_name'   => 'required|string|max:255',
            'applicant_email'   => 'required|email|max:255',
            'print_name'       => 'required|string|max:255',
            'address'          => 'required|string',
            'certificate_type' => 'required|string',
        ]);

        try {
            ContactDakhala::create($data);

            return redirect()->back()
                ->with('dakhala_success', 'आपला अर्ज यशस्वीरित्या सबमिट झाला आहे.')
                ->withFragment('dakhala');

        } catch (\Exception $e) {

            \Log::error($e);

            return redirect()->back()
                ->with('dakhala_error', 'काहीतरी चूक झाली. कृपया पुन्हा प्रयत्न करा.')
                ->withFragment('dakhala');
        }
    }
    
}
