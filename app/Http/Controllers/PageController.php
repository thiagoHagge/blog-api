<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Page;
use App\Http\Helpers;

class PageController extends Controller
{
    public $Helpers;
    
    public function __construct() 
    {
        $this->Helpers = new Helpers;
    }

    public function createPage(Request $req)
    {
        // Create link and check if already exists
        $link = $this->Helpers->cleanString($req->name);
        if(Page::where('pg_link' ,$link)->first()) {
            return response()->json(['error' => 'Nome indisponível', 'success' => false]);
        }

        // Set parent
        $parent = empty($req->parent) ? 0 : $req->parent;

        // Set order
        if(empty($req->order) && $req->order !== 0) {
            $order = Page::where('pg_order', '>', 0)->where('pg_parent', $parent)->count() + 1;
        } else {
            $order = $req->order;
        }

        // Crate page
        Page::create(['pg_link' => $link, 'pg_name' => $req->name, 'pg_parent' => $parent, 'pg_order' => $order]);
        return response()->json(['success' => true]);
    }

    public function readPages()
    {
        $pages = Page::selectRaw('pg_id as id, pg_link as link, pg_name as name, pg_order as position')->where('pg_order', '>', 0)->where('pg_parent', 0)->orderBy('pg_order')->get()->toArray();
        foreach($pages as $key => $page) {
            $pages[$key]['children'] = Page::selectRaw('pg_id as id, pg_link as link, pg_name as name, pg_order as position')
                ->where('pg_order', '>', 0)
                ->where('pg_parent', $page['id'])
                ->orderBy('pg_order')
                ->get()
                ->toArray();
        }
        return response()->json(['success' => true, 'pages' => $pages]);
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
        $page = Page::selectRaw('pg_content as content, pg_name as title')->where('pg_link', $req->page)->first();
        
        // return var_dump($page);
        if(!$page) {
            return response()->json(['error' => 'Página não encontrada', 'success' => false]);
        }
        return response()->json(['success' => true, 'content' => $page->content == null ? '' : $page->content, 'title' => $page->title]);
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
