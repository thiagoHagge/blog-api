<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Carousel;

class CarouselController extends Controller
{
    public function createItem(Request $req) {
        $thisCarousel = ['crsl_title' => $req->title, 'crsl_subtitle' => $req->subtitle];
        if(!empty($req->image)) {
            $imageName = time().'.'.$req->image->extension();  
            // Upload image
            $req->image->move(public_path('images'), $imageName);
            $thisCarousel['crsl_image'] = $imageName;
        }
        if(!empty($req->id)) {
            Carousel::find($req->id)->update($thisCarousel);
        } else {
            if(!isset($thisCarousel['crsl_image'])) {
                return response()->json(['error' => 'Nenhuma imagem foi enviada', 'success' => false]);
            }
            Carousel::create($thisCarousel);
        }
        // var_dump($req->image);
        // exit;
        return $this->getItems();
    }

    public function getItems() {
        $items = Carousel::selectRaw('crsl_id as id, crsl_title as title, crsl_subtitle as subtitle, crsl_image as image')
            ->get();
        return response()->json(['success' => true, 'items' => $items]);
    }

    public function delete(Request $req) {
        $carousel = Carousel::find($req->id)->delete();
        if(!$carousel) {
            return response()->json(['error' => 'Carousel não encontrado', 'success' => false]);
        }
        
        return $this->getItems();
    }

}
