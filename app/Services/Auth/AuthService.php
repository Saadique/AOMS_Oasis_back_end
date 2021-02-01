<?php

namespace App\Services\Auth;
use App\Role;
use App\Services\Service;
use App\View;

class AuthService extends Service
{
    public function getAuthorizedViews($roleId) {
        $role = Role::findOrFail($roleId);
        $views = $role->views;
        $onlyParent = $this->push0LevelParent($views);
        $finalResult = $this->takeChildrenOfChildren($onlyParent, $roleId);
        return $finalResult;
    }

	//this function pushes all the views which don’t have a parent(top level)
    public function push0LevelParent($views)
    {
        $withChildren = [];
        foreach ($views as $view) {
            if ($view->parent_id == 0) {
                array_push($withChildren, $view);
            }
        }
        return $withChildren;
    }


    public function takeChildrenOfChildren($withChildren, $roleId) {
       foreach ($withChildren as $obj){
               $children = View::where('parent_id', $obj->id)->get();
               if (sizeof($children) != 0) {
                   $role = Role::findOrFail($roleId);
                   $views = $role->views;
                   $authorizedChildren = $this->filterAuthorizedChildren($children, $views);
                   if (sizeof($authorizedChildren)!=0) {
                       $obj->{"children"} = $authorizedChildren;
                       $this->takeChildrenOfChildren($authorizedChildren,$roleId);
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
