<?php

namespace App\Services\Auth;
use App\Role;
use App\Services\Service;
use App\View;

class abdullah_test extends Service
{
    public $i =0;

    public function getAuthorizedViews($roleId)
    {
        $role = Role::findOrFail($roleId);
        $views = $role->views;

        $withChildren = [];
//        array_push($withChildren,$this->checkChildren($views));
        $withChildren = $this->checkChildren($views,true);



//        foreach ($views as $view) {
//            $children = View::where('parent_id',$view->id)->get();
//
//
//
////            $children = DB::select("SELECT * FROM views WHERE parent_id=$view->id AND
////                                          $view->id IN (SELECT view_id FROM role_view WHERE role_id=$roleId)");
//            $authorizedChildren = [];
//            $authorizedChildren = $this->checkAuthorize($children,$views,$view);
//
//
//
//
////            array_push($authorizedChildren,$this->checkAuthorize($children,$views,$view));
////            foreach ($children as $child){
////                foreach ($views as $view2){
////                    if ($child->id == $view2->id){
////                        array_push($authorizedChildren, $child);
////                        break;
////                    }
////                }
////            }
//            $temp = $view;
//            if (sizeof($authorizedChildren)!=0){
//                $temp->{"children"} = $authorizedChildren;
//            }else{
//                $temp->{"children"} = null;
//            }
//            if ($temp->parent_id == 0){
//                array_push($withChildren, $temp);
//            }
//        }
        return $withChildren;
    }

    public function checkAuthorize($children, $views, $view)
    {

        $authorizedChildren = [];
        foreach ($children as $child) {

            foreach ($views as $view2) {
                if ($child->id == $view2->id) {
                    array_push($authorizedChildren, $child);
                    break;
                }
            }
        }
        return $authorizedChildren;
    }

    public function checkChildren($views, $check)
    {
//        echo "checkchild";

        $withChildren = [];
        foreach ($views as $view) {
            if ($this->i != 0) {

//                echo "view \n";
//                echo $view;
//                echo "view end \n";

            }
            $children = View::where('parent_id',$view->id)->get();
//            $children = DB::select("SELECT * FROM views WHERE parent_id=$view->id AND
//                                          $view->id IN (SELECT view_id FROM role_view WHERE role_id=$roleId)");
            $authorizedChildren = [];
            $auth=$this->checkAuthorize($children,$views,$view);


            $authorizedChildren=$auth;


            foreach ($children as $kid) {

                $subchildren = View::where('parent_id', $kid->id)->get();
                foreach ($subchildren as $sub){
//                    echo "sub \n";
//                    echo "kid";
//                    echo $kid;
//                    echo "kid end \n";
//
//                    echo $sub;
//                    echo "sub end \n";

                }
                if (sizeof($subchildren) != 0) {
//                    echo "cildren";
                    $this->i = 1;
                    $t=$this->checkChildren($subchildren, false);
//                    var_dump($t);
                    foreach ($t as $tt){
                        foreach ( $auth as $aut)
                            $aut->{"children"} = $tt;
//                        echo "test";
//                        echo $tt;
//                        echo "test end";
                    }
                }
            }


            $temp = $view;
            if (sizeof($auth)!=0){
                $temp->{"children"} = $auth;
//
            }else{
                $temp->{"children"} = null;
            }

            if ($check == true){
                if ($temp->parent_id == 0){
                    array_push($withChildren, $temp);
                }

            }
            else {
//                echo "check";
//                echo $temp;
                array_push($withChildren, $temp);
            }
//

        }

        return $withChildren;
    }
}
