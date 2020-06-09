<?php

namespace App\Http\Controllers\Api\V0;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;

class PermissionController extends Controller
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
        $permissions = Permission::orderBy('id', 'desc')->paginate($request->query('per_page', 15));
        return json($permissions);
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
            $count = Permission::where('name', $value)
                                ->where('guard_name', $parameters[0])
                                ->count();
            return $count === 0;
        });
        $request->validate([
            'name'          => 'required|string|max:255|uniqueNameAndGuard:' . $request->guard_name,
            'guard_name'    => 'required|string|max:255',
        ], [
            'unique_name_and_guard' => '记录已经存在',
        ]);

        $permission = new Permission($request->all());
        $permission->save();

        return json($permission, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $permission = Permission::findOrFail($id);
        return json($permission);
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
            $count = Permission::where('name', $value)
                                ->where('guard_name', $parameters[0])
                                ->first();
            if (!$count) return true;
            if ($count->id != $parameters[1]) return false;
            else return true;
        });
        $request->validate([
            'name'          => 'required|string|max:255|uniqueNameAndGuardExcept:' . $request->guard_name . ',' .$id,
            'guard_name'    => 'required|string|max:255',
        ], [
            'unique_name_and_guard_except' => '记录已经存在',
        ]);

        $permission = Permission::findOrFail($id);
        $permission->fill($request->input());
        $permission->save();

        return json( $permission, 201 );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $permission = Permission::findOrFail($id);
        $permission->delete();
        return json( null, 204 );
    }
}
