<?php

namespace App\Http\Controllers\View;

use App\Http\Controllers\ApiController;
use App\View;
use Illuminate\Http\Request;

class ViewController extends ApiController
{
    public function index()
    {
        return View::all();
    }

    public function menu()
    {
        $views = View::all();
        $withChildren = [];
        foreach ($views as $view) {
            $children = View::where('parent_id',$view->id)->get();
            $temp = $view;
            if (sizeof($children)!=0){
                $temp->{"children"} = $children;
            }
            if ($temp->parent_id == 0) {
                array_push($withChildren, $temp);
            }
        }
        return $withChildren;
    }

    public function create()
    {

    }

    public function store(Request $request)
    {

    }

    public function show(View $view)
    {

    }

    public function edit(View $view)
    {

    }


    public function update(Request $request, View $view)
    {

    }

    public function destroy(View $view)
    {

    }
}
