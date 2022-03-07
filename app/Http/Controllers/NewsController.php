<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News;
use Illuminate\Support\Facades\Storage;

class NewsController extends Controller
{
    // TODO: colocar clean string em local reutilizável 
    public function cleanString($string) {
        $newString = preg_replace(
            array(
                "/(á|à|ã|â|ä)/",
                "/(Á|À|Ã|Â|Ä)/",
                "/(é|è|ê|ë)/",
                "/(É|È|Ê|Ë)/",
                "/(í|ì|î|ï)/",
                "/(Í|Ì|Î|Ï)/",
                "/(ó|ò|õ|ô|ö)/",
                "/(Ó|Ò|Õ|Ô|Ö)/",
                "/(ú|ù|û|ü)/",
                "/(Ú|Ù|Û|Ü)/",
                "/(ñ)/","/(Ñ)/"
            ),
            explode(" ", "a A e E i I o O u U n N"),
            $string
        );
        $newString = str_replace('ç', 'c', $newString);
        $newString = trim($newString);
        $newString = strtolower($newString);
        $newString = str_replace(' ', '-', $newString);
        return $newString;
    }

    public function create(Request $req)
    {
        // Create link and check if already exists
        
        $slug = $this->cleanString($req->title);
        if(News::where('news_slug' ,$slug)->first() && empty($req->link)) {
            return response()->json(['error' => ['component' => 'title', 'message' => 'Título já utilizado'], 'success' => false]);
        }
        $thisNews = ['news_slug' => $slug, 'news_title' => $req->title, 'news_content' => $req->content == null ? '' : $req->content];

        // Author
        if(!empty($req->author)) {
            $thisNews['news_author'] = $req->author;
        }
        
        // Upload image
        if(!empty($req->image)) {
            $path = Storage::disk('s3')->put('images', $req->image);
            $path = Storage::disk('s3')->url($path);
            $thisNews['news_image'] = $path;
        }

        // youtube video
        if(!empty($req->videoLink)) {
            $ytId = explode('v=', $req->videoLink);
            if(!isset($ytId[1])) {
                return response()->json(['error' => ['component' => 'video', 'message' => 'Link inválido'], 'success' => false]);
            }
            $ytId = explode('&', $ytId[1])[0];
            $thisNews['news_ytId'] = $ytId;
        }
        // return response()->json(['success' => true, 'title' => $req->title, $req->content, $imageName]);
        // // Crate page
        if(!empty($req->link)) {
            News::where('news_slug', $req->link)->update($thisNews);
            return response()->json(['success' => true, 'link' => $slug]);
        } else {
            // Crate page
            News::create($thisNews);
            return response()->json(['success' => true, 'link' => $slug]);
        }

    }

    public function read($limit = false, $video = false, $slug = false)
    {
        $select = 'news_id as id,news_slug as link, news_title as title, news_content as content, news_image as image, news_author as author, news_creation as creation, news_updated as updated';
        $select .= $video != false ? ', news_ytId as ytId' : '';
        $news = News::selectRaw($select);
        $video == false ? $news->whereNull('news_ytId') : $news->where('news_ytId', '!=',null);
        if($slug != false) {
            $news->where('news_slug', $slug);
        }
        $news->orderBy('news_creation', 'DESC');
        if($limit != false) {
            $news->limit($limit); 
        }
        
        return response()->json(['success' => true, 'news' => $slug != false ? $news->first() : $news->get()]);
    }

    public function readLimit(Request $req)
    {
        $limit = $req->limit;
        return $this->read(intval($limit));
    }

    public function readItem(Request $req)
    {
        return $this->read(false, false, $req->slug);
    }

    public function delete(Request $req) {
        $delete = News::find($req->id)->delete();
        if(!$delete) {
            return response()->json(['error' => 'Erro ao excluir', 'success' => false]);
        }
        
        return response()->json(['success' => true]);
    }

    public function getVideosLimit(Request $req) {
        return $this->read(intval($req->limit), true);
    }

    public function getVideos() {
        return $this->read(false, true);
    }

    public function getVideo(Request $req) {
        return $this->read(false, true, $req->slug);
    }

}
