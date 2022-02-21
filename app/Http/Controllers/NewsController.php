<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News;

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
        if(News::where('news_slug' ,$slug)->first()) {
            return response()->json(['error' => 'Título já utilizado', 'success' => false]);
        }
        $thisNews = ['news_slug' => $slug, 'news_title' => $req->title, 'news_content' => $req->content];

        // Upload image
        if(!empty($req->image)) {
            $imageName = time().'.'.$req->image->extension();  
            
            $req->image->move(public_path('images'), $imageName);
            $thisNews['news_image'] = $imageName;
        }

        if(!empty($req->link)) {
            News::where('news_slug', $req->link)->update($thisNews);
            return response()->json(['success' => true, 'link' => $slug]);
        } else {
            // Crate page
            News::create($thisNews);
            return response()->json(['success' => true, 'link' => $slug]);
        }

    }

    public function read()
    {
        $news = News::selectRaw('news_slug as link, news_title as title, news_image as image')->get();
        return response()->json(['success' => true, 'news' => $news]);
    }

    public function readItem(Request $req)
    {
        $news = News::selectRaw('news_content as content, news_title as title, news_image as image')->where('news_slug', $req->slug)->first();
        return response()->json(['success' => true, 'news' => $news]);
    }

    public function updatePage(Request $req)
    {
        $page = Page::where('pg_link', $req->page)->update(['pg_content' => $req->content]);
        $return = $page ? ['success' => true] : ['error' => 'Erro ao atualizar página', 'success' => false];
        return response()->json($return);
    }

    public function readPage(Request $req)
    {
        // return $req->page;
        $page = Page::selectRaw('pg_content as content')->where('pg_link', $req->page)->first();
        if(!$page) {
            return response()->json(['error' => 'Página não encontrada', 'success' => false]);
        }
        return response()->json(['success' => true, 'content' => $page->content]);
    }

    public function setOrder(Request $req)
    {
        $pageLink = $req->page;
        $newPosition = $req->position;
        $parent = $req->parent;
        $pages = Page::selectRaw('pg_link as link, pg_name as name, pg_order as position')
            ->where('pg_order', '>', 0)
            ->where('pg_parent', $parent)
            ->orderBy('pg_order')
            ->get()
            ->toArray();
            
        foreach($pages as $page) {
            if($page['link'] == $pageLink) {
                $actualPosition = $page['position'];
                break;
            }
        }
        if($newPosition == $actualPosition) {
            return response()->json(['success' => true, 'pages' => $pages]);
        }
        
        $add = $newPosition > $actualPosition ? -1 : 1;
        if ($newPosition > $actualPosition) {
            while($actualPosition < $newPosition) {
                Page::where('pg_order', ++$actualPosition)
                    ->where('pg_parent', $parent)
                    ->update(['pg_order' => $actualPosition - 1]);
            }
        } else {
            while($newPosition < $actualPosition) {
                Page::where('pg_order', --$actualPosition)
                    ->where('pg_parent', $parent)
                    ->update(['pg_order' => $actualPosition + 1]);
            }
        }
        Page::where('pg_link', $pageLink)->update(['pg_order' => $newPosition]);

        $pages = Page::selectRaw('pg_link as link, pg_name as name, pg_order as position')
            ->where('pg_order', '>', 0)
            ->where('pg_parent', $parent)
            ->orderBy('pg_order')
            ->get()
            ->toArray();

        return response()->json(['success' => true, 'pages' => $pages]);
        // var_dump($pages);exit;
        // if(!$pages) {
        //     return response()->json(['error' => 'Página não encontrada', 'success' => false]);
        // }
        // return response()->json(['success' => true, 'content' => $page->content]);
    }

    public function deletePage(Request $req) {
        $page = Page::where('pg_link', $req->page)->delete();
        $pages = Page::selectRaw('pg_link as link, pg_name as name, pg_order as position')->where('pg_order', '>', 0)->where('pg_parent', 0)->orderBy('pg_order')->get();
        if(!$page) {
            return response()->json(['error' => 'Página não encontrada', 'success' => false, 'pages' => $pages]);
        }
        
        return response()->json(['success' => true, 'pages' => $pages]);
    }

}
