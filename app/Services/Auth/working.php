<?php

namespace App\Services\Auth;
use App\Role;
use App\Services\Service;
use App\View;

class working extends Service
{
    public function getAuthorizedViews($roleId) {
        $role = Role::findOrFail($roleId);
        $views = $role->views;
        $withChildren = $this->findChildren($views);
        $finalResult = $this->takeChildrenOfChildren($withChildren, $roleId);
        return $finalResult;
    }


    public function findChildren($views)
    {
        $withChildren = [];
        foreach ($views as $view) {
            $isChildren = false;
            $children = View::where('parent_id', $view->id)->get();
            if (sizeof($children) != 0) {
                $authorizedChildren = $this->filterAuthorizedChildren($children, $views);
                if (sizeof($authorizedChildren)!=0) {
                    $isChildren = true;
                    $view->{"children"} = $authorizedChildren;
                }else {
                    $view->{"children"} = null;
                }
            }
            if ($view->parent_id == 0) {
                array_push($withChildren, $view);
            }
        }
        return $withChildren;
    }

    public function takeChildrenOfChildren($withChildren, $roleId) {
        foreach ($withChildren as $obj){
            if ($obj->children!=null){
                foreach ($obj->children as $child){
                    $children = View::where('parent_id', $child->id)->get();
                    if (sizeof($children) != 0) {
                        $role = Role::findOrFail($roleId);
                        $views = $role->views;
                        $authorizedChildren = $this->filterAuthorizedChildren($children, $views);
                        if (sizeof($authorizedChildren)!=0) {
                            $child->{"children"} = $authorizedChildren;
                        }else {
                            $child->{"children"} = null;
                        }
                    }
                }
            }
        }

        return $withChildren;
    }


    public function filterAuthorizedChildren($children, $views)
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


}
