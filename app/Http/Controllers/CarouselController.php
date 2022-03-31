<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Carousel;
use App\Http\Helpers;

class CarouselController extends Controller
{
    public $Helpers;
    
    public function __construct() 
    {
        $this->Helpers = new Helpers;
    }

    public function createItem(Request $req) {
        $thisCarousel = ['crsl_title' => $req->title, 'crsl_subtitle' => $req->subtitle];
        
        // Upload image
        !empty($req->image) && $thisCarousel['crsl_image'] = $this->Helpers->createImageLink($req->image);
        
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
        return response()->json(['success' => true, 'items' => $this->getItems()]);
    }

    public function getItems() {
        $items = Carousel::selectRaw('crsl_id as id, crsl_title as title, crsl_subtitle as subtitle, crsl_image as image')
            ->get();
        return $items;
    }

    public function delete(Request $req) {
        $carousel = Carousel::find($req->id)->delete();
        if(!$carousel) {
            return response()->json(['error' => 'Carousel não encontrado', 'success' => false]);
        }
        
        return response()->json(['success' => true, 'items' => $this->getItems()]);
    }

}
