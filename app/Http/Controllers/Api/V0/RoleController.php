<?php

namespace App\Http\Controllers\Api\V0;

use App\Models\Admin;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->validate([
            'page' => 'integer|min:1',
            'per_page' => 'integer|min:1|max:100',
        ]);
        $roles = Role::orderBy('id', 'desc')->paginate($request->query('per_page', 15));
        return json($roles);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Validator::extend('uniqueNameAndGuard', function ($attribute, $value, $parameters, $validator) {
            $count = Role::where('name', $value)
                            ->where('guard_name', $parameters[0])
                            ->count();
            return $count === 0;
        });
        $request->validate([
            'name'          => 'required|string|max:255|uniqueNameAndGuard:' . $request->guard_name,
            'guard_name'    => 'required|string|max:255',
            'permissions.*' => 'integer|distinct|exists:permissions,id',
        ], [
            'unique_name_and_guard' => '记录已经存在',
        ]);

        $role = new Role();
        $role->name = $request->name;
        $role->guard_name = $request->guard_name;
        $role->save();
        $role->permissions()->attach($request->permissions);

        return json($role, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $role = Role::findOrFail($id);
        $role->permissions;
        return json($role);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        Validator::extend('uniqueNameAndGuardExcept', function ($attribute, $value, $parameters, $validator) {
            $count = Role::where('name', $value)
                            ->where('guard_name', $parameters[0])
                            ->first();
            if (!$count) return true;
            if ($count->id != $parameters[1]) return false;
            else return true;
        });
        $request->validate([
            'name'          => 'required|string|max:255|uniqueNameAndGuardExcept:' . $request->guard_name . ',' .$id,
            'guard_name'    => 'required|string|max:255',
            'permissions.*' => 'integer|distinct|exists:permissions,id',
        ], [
            'unique_name_and_guard_except' => '记录已经存在',
        ]);

        $role = Role::findOrFail($id);
        $role->name = $request->name;
        $role->guard_name = $request->guard_name;
        $role->save();
        $role->permissions()->sync($request->permissions);

        return json( $role, 201 );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        return json( null, 204 );
    }
}
