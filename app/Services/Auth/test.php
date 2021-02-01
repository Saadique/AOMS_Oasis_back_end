<?php

namespace App\Services\Auth;
use App\Role;
use App\Services\Service;
use App\View;
use Illuminate\Support\Facades\DB;

class test extends Service
{
    public function getAuthorizedViews($roleId) {
        $role = Role::findOrFail($roleId);
        $views = $role->views;
        $withChildren = [];
//        array_push($withChildren,$this->checkChildren($views));
        foreach ($views as $view) {
            $children = View::where('parent_id',$view->id)->get();
//            $children = DB::select("SELECT * FROM views WHERE parent_id=$view->id AND
//                                          $view->id IN (SELECT view_id FROM role_view WHERE role_id=$roleId)");
            $authorizedChildren = [];
//            array_push($authorizedChildren,$this->checkAuthorize($children,$views,$view));
            foreach ($children as $child){
                foreach ($views as $view2){
                    if ($child->id == $view2->id){
                        array_push($authorizedChildren, $child);
                        break;
                    }
                }
            }
            $temp = $view;
            if (sizeof($authorizedChildren)!=0){
                $temp->{"children"} = $authorizedChildren;
            }
            if ($temp->parent_id == 0){
                array_push($withChildren, $temp);
            }
        }
        return $withChildren;
    }

    public function checkAuthorize($children,$views,$view){

        $authorizedChildren = [];
        foreach ($children as $child){

            foreach ($views as $view2){
                if ($child->id == $view2->id){
                    array_push($authorizedChildren, $child);
                    break;
                }
            }
        }


        return $authorizedChildren;
    }

    public function checkChildren($views){
        $withChildren = [];
        foreach ($views as $view) {
            $children = View::where('parent_id',$view->id)->get();
//            $children = DB::select("SELECT * FROM views WHERE parent_id=$view->id AND
//                                          $view->id IN (SELECT view_id FROM role_view WHERE role_id=$roleId)");
            $authorizedChildren = [];
            $auth=$this->checkAuthorize($children,$views,$view);

//            array_push($authorizedChildren,$auth);
            $authorizedChildren=$auth;

//            foreach ($children as $child){
//                foreach ($views as $view2){
//                    if ($child->id == $view2->id){
//                        array_push($authorizedChildren, $child);
//                        break;
//                    }
//                }
//            }
            foreach ($children as $kid) {
                $subchildren = View::where('parent_id', $kid->id)->get();
                if (sizeof($subchildren) != 0) {
//                    $t=$this->checkChildren($subchildren);

//                    if (sizeof($auth)!=0){
//                        foreach ($auth as $aut) {
//                            $aut->{"children"} = [];
//                            foreach ($t as $tt){
//                                array_push($aut->children,$tt);
//                            }
//                        }
//                    }

//                    foreach ($auth as $aut){
//                        if($aut->parent_id == $kid->id){
//                            $aut->{"children"}=$t;
//                        }
//                    }
                }
            }


            $temp = $view;
            if (sizeof($auth)!=0){
                $temp->{"children"} = $auth;
//                foreach($withChildren as $withChild) {
//                    if ($withChild->children!=null && $auth != null) {
//                        foreach ($withChild->children as $child) {
//                            echo $child;
//                            foreach ($auth as $aut){
//                            if ($child->id == $auth->id) {
//                                array_push($child->{"children"}, $temp);
//                            }
//                        }
//                        }
//                    }
//                }
            }else{
                $temp->{"children"} = null;
            }
            if ($temp->parent_id == 0){
                array_push($withChildren, $temp);
            }else{

            }
//            $temp = $view;
//            if (sizeof($authorizedChildren)!=0){
//                $temp->{"children"} = $authorizedChildren;
//            }
//            if ($temp->parent_id != 0){
//                if (sizeof($authorizedChildren)!=0){
//                $temp->{"children"} = $authorizedChildren;
//            } else {
//                    $temp->{"children"} = null;
//                }
//            }

        }

        return $withChildren;
    }
}
