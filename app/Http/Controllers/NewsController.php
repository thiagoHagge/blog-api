<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News;
use App\Http\Helpers;

class NewsController extends Controller
{
    public $Helpers;
    
    public function __construct() 
    {
        $this->Helpers = new Helpers;
    }

    public function create(Request $req)
    {
        $title = $req->title;
        $thisNews = [];

        // Podcast
        if(!empty($req->podcast)) {
            $title = $this->Helpers->getUrlTitle($req->podcast);
            $thisNews['news_podcast'] = $req->podcast;
        }

        // Create link and check if already exists
        $slug = $this->Helpers->cleanString($title);
        if(News::where('news_slug' ,$slug)->first() && empty($req->link)) {
            return response()->json(['error' => ['component' => 'title', 'message' => 'Título já utilizado'], 'success' => false]);
        }
        $thisNews += ['news_slug' => $slug, 'news_title' => $title, 'news_content' => $req->content == null ? '' : $req->content];
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

        // Save on DB
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

    public function read($limit = false, $video = false, $slug = false, $podcast = false)
    {
        $select = 'news_id as id,news_slug as link, news_title as title, news_content as content, news_image as image, news_author as author, news_creation as creation, news_updated as updated';
        $select .= $video != false ? ', news_ytId as ytId' : '';
        $select .= $podcast != false ? ', news_podcast as podcast' : '';
        $news = News::selectRaw($select);
        $video == false ? $news->whereNull('news_ytId') : $news->where('news_ytId', '!=',null);
        $podcast == false ? $news->whereNull('news_podcast') : $news->where('news_podcast', '!=',null);
        if($slug != false) {
            $news->where('news_slug', $slug);
        }
        $news->orderBy('news_creation', 'DESC');
        if($limit != false) {
            $news->limit($limit); 
        }
        
        return ['success' => true, 'news' => $slug != false ? $news->first() : $news->get()];
    }

    public function readLimit(Request $req, $limit = 0)
    {
        $returnJson = false;
        if($limit == 0) {
            $returnJson = true;
            $limit = $req->limit;
        }
        
        return $returnJson ? response()->json($this->read(intval($limit))) : $this->read(intval($limit));
    }

    public function readItem(Request $req)
    {
        $return = $this->read(false, false, $req->slug);
        $return['lastNews'] = $this->read(4)['news'];
        return response()->json($return);
    }

    public function delete(Request $req) {
        $delete = News::find($req->id)->delete();
        if(!$delete) {
            return response()->json(['error' => 'Erro ao excluir', 'success' => false]);
        }
        
        return response()->json(['success' => true]);
    }

    public function getVideosLimit(Request $req, $limit = 0) {
        $returnJson = false;
        if($limit == 0) {
            $returnJson = true;
            $limit = $req->limit;
        }

        return $returnJson ? response()->json($this->read(intval($limit), true)) : $this->read(intval($limit), true);
    }

    public function getVideos() {
        return response()->json($this->read(false, true));
    }

    public function getVideo(Request $req) {
        $return = $this->read(false, true, $req->slug);
        $return['lastVideos'] = $this->read(4, true)['news'];
        return response()->json($return);
    }

    public function getPodcasts() {
        return response()->json($this->read(false, false, false, true));
    }

    public function getPodcastsLimit(Request $req, $limit = 0) {
        $returnJson = false;
        if($limit == 0) {
            $returnJson = true;
            $limit = $req->limit;
        }
        return $returnJson ? response()->json($this->read(intval($req->limit), false, false, true)) : $this->read(intval($req->limit), false, false, true);
    }

    public function getLandingPage(Request $req) {
        function getResult($arr) {
            if ($arr['success'] == 0) {
                throw new Exception('Erro ao buscar dados');
            }
            return $arr['news'];
        }
        try {
            $data['carousel'] = app('App\Http\Controllers\CarouselController')->getItems();
            $data['news'] = getResult($this->readLimit($req, 4));
            $data['videos'] = getResult($this->getVideosLimit($req, 3));
            $data['podcasts'] = getResult($this->getPodcastsLimit($req, 3));
            $data['success'] = 1;
            return response()->json($data);
        } catch (Exception $e) {
            return response()->json(['success' => 0, 'error' => $e->getMessage()], 500);
        }
    }

}
