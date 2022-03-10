<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News;
use App\Http\Helpers;

class NewsController extends Controller
{
    public $this->Helpers;
    
    public function __construct() 
    {
        $this->Helpers = new Helpers;
    }

    public function create(Request $req)
    {
        // Create link and check if already exists
        
        $slug = $this->Helpers->cleanString($req->title);
        if(News::where('news_slug' ,$slug)->first() && empty($req->link)) {
            return response()->json(['error' => ['component' => 'title', 'message' => 'Título já utilizado'], 'success' => false]);
        }
        $thisNews = ['news_slug' => $slug, 'news_title' => $req->title, 'news_content' => $req->content == null ? '' : $req->content];

        // Author
        if(!empty($req->author)) {
            $thisNews['news_author'] = $req->author;
        }
        
        // Upload image
        !empty($req->image) && $thisNews['news_image'] = $this->Helpers->createImageLink($req->image);

        // youtube video
        if(!empty($req->videoLink)) {
            $ytId = explode('v=', $req->videoLink);
            if(!isset($ytId[1])) {
                return response()->json(['error' => ['component' => 'video', 'message' => 'Link inválido'], 'success' => false]);
            }
            $ytId = explode('&', $ytId[1])[0];
            $thisNews['news_ytId'] = $ytId;
        }
        if(!empty($req->link)) {
            // UPDATE
            News::where('news_slug', $req->link)->update($thisNews);
            return response()->json(['success' => true, 'link' => $slug]);
        } else {
            // CREATE
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
