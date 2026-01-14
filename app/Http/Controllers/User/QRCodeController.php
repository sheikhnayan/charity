<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class QRCodeController extends Controller
{
    public function index()
    {
        // Redirect to admin QR codes
        return redirect('/qr-codes');
    }

    public function generate(Request $request)
    {
        return redirect('/qr-codes');
    }

    public function download($id)
    {
        return redirect('/qr-codes');
    }
}
