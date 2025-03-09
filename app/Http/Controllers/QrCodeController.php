<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\Equipment;

class QrCodeController extends Controller
{
    public function generateQRCodesPDF()
    {
        $records = Equipment::where('status','active')->get();
        $qrcode = [];
            foreach ($records as $record) {
            $url = route('equipment.show', $record->id); 
            $qrcode[]=QrCode::size(100)->generate($url);
            }
        return view('qrcode_pdf',compact('qrcode','record'));

    }
    
}