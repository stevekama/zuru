<?php

namespace App\Http\Controllers\Backend;

use App\Permission;
use App\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

class AccessController extends Controller
{
    //

    public function list()
    {

        return View::make('backend.roles.index')->withRoles(Role::all());

    }

    public function creteRole()
    {
        return View::make('backend.roles.form');

    }

    public function storeRole(Request $request)
    {

        if($request->has('id') && $request->input('id')!=null){
            $admin = Role::find(request('id'));
        }else{
            $admin = new Role();
        }

        $admin->name         = request('name');
        $admin->display_name = request('display_name');
        $admin->description  = request('description');
        $admin->save();
    }

    public function editRole(Role $role)
    {
        Session::flash('_old_input',$role);
        return View::make('backend.roles.form');

    }

    public function assignForm(Role $role)
    {
        return View::make('backend.roles.assign')->withPermissions(Permission::all())->withRole($role);

    }

    public function storeAssign(Request $request)
    {
        $role = Role::find(request('role_id'));
        $permissions = Permission::wherein('id',request('permissions'))->get();
        $_permissions = $role->permissions()->get();
        $role->permissions()->detach($_permissions);
        $role->permissions()->attach($permissions);

        return back();
    }
}
