<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;
use App\Http\Helpers;

class ContactController extends Controller
{
    public function update(Request $req) {
        $contact = Contact::where('phone', '<>', null)->update([
            'phone' => $req->phone != null ? $req->phone : '',
            'email' => $req->email != null ? $req->email : '',
            'whatsapp' => $req->whatsapp != null ? $req->whatsapp : '',
            'whatsappMessage' => $req->whatsappMessage != null ? $req->whatsappMessage : '',
            'facebook' => $req->facebook != null ? $req->facebook : '', 
            'instagram' => $req->instagram != null ? $req->instagram : '',
            'spotify' => $req->spotify != null ? $req->spotify : '',
            'youtube' => $req->youtube != null ? $req->youtube : ''
        ]);
        return response()->json(['success' => $contact]);
    }

}
