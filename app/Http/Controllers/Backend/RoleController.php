<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    // Metodo per ottenere tutti i permessi
    public function Permissions(){
        $permissions = Permission::all();
        return view('backend.pages.permission.permissions',compact('permissions'));
    }

    // Metodo per visualizzare la pagina di aggiunta di un nuovo permesso
    public function Add(){
        return view('backend.pages.permission.add');
    }

    // Metodo per salvare un nuovo permesso
    public function Store(Request $request){
        $role = Permission::create([
            'name' => $request->name,
            'group_name' => $request->group_name,
        ]);

        $notification = array(
            'message' => 'Permission inserted',
            'alert-type' => 'success'
        );

        return redirect()->route('permissions')->with($notification); 
    }

    // Metodo per visualizzare la pagina di modifica di un permesso
    public function Edit($id){
       $permission = Permission::findOrFail($id);
       return view('backend.pages.permission.edit',compact('permission'));
    }

    // Metodo per aggiornare un permesso esistente
    public function Update(Request $request){
        $per_id = $request->id;

        Permission::findOrFail($per_id)->update([
            'name' => $request->name,
            'group_name' => $request->group_name,
        ]);

        $notification = array(
            'message' => 'Permission updated',
            'alert-type' => 'success'
        );
        return redirect()->route('permissions')->with($notification); 
    }

    // Metodo per eliminare un permesso
    public function Delete($id){
        Permission::findOrFail($id)->delete();
        $notification = array(
            'message' => 'Permission deleted',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification); 
    } 

    // Metodo per ottenere tutti i ruoli
    public function Roles(){
        $roles = Role::all();
        return view('backend.pages.roles.roles',compact('roles'));
    }

    // Metodo per visualizzare la pagina di aggiunta di un nuovo ruolo
    public function AddRoles(){
        return view('backend.pages.roles.add');
    }

    // Metodo per salvare un nuovo ruolo
    public function StoreRoles(Request $request){
        $role = Role::create([
            'name' => $request->name, 
        ]);

        $notification = array(
            'message' => 'Role inserted',
            'alert-type' => 'success'
        );
        return redirect()->route('roles')->with($notification); 
    }

    // Metodo per visualizzare la pagina di modifica di un ruolo
    public function EditRoles($id){
        $roles = Role::findOrFail($id);
        return view('backend.pages.roles.edit',compact('roles'));
    }

    // Metodo per aggiornare un ruolo esistente
    public function UpdateRoles(Request $request){
        $role_id = $request->id; 

        Role::findOrFail($role_id)->update([
            'name' => $request->name, 
        ]);

        $notification = array(
            'message' => 'Roles updated',
            'alert-type' => 'success'
        );

        return redirect()->route('roles')->with($notification); 
    }

    // Metodo per eliminare un ruolo
    public function DeleteRoles($id){
       Role::findOrFail($id)->delete();
       $notification = array(
            'message' => 'Roles deleted',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification); 
    }

    // Metodo per visualizzare la pagina di aggiunta di permessi a un ruolo
    public function AddRolesPermission(){
         $roles = Role::all();
         $permissions = Permission::all();
         $permission_groups = User::getpermissionGroups();
         return view('backend.pages.roles.add_roles_permission',compact('roles','permissions','permission_groups'));
    }

    // Metodo per salvare i permessi di un ruolo
    public function RolePermissionStore(Request $request){
        $data = array();
        $permissions = $request->permission;

        foreach($permissions as $key => $item){
            $data['role_id'] = $request->role_id;
            $data['permission_id'] = $item;
            DB::table('role_has_permissions')->insert($data);
        }

         $notification = array(
            'message' => 'Role permission added',
            'alert-type' => 'success'
        );

        return redirect()->route('roles.permissions')->with($notification); 
    }

    // Metodo per ottenere tutti i ruoli con i loro permessi
    public function AllRolesPermission(){
        $roles = Role::all();
        return view('backend.pages.roles.roles_permission',compact('roles'));
    }

    // Metodo per visualizzare la pagina di modifica dei permessi di un ruolo
    public function AdminRolesEdit($id){
        $role = Role::findOrFail($id);
        $permissions = Permission::all();
        $permission_groups = User::getpermissionGroups();
        return view('backend.pages.roles.role_permission_edit',compact('role','permissions','permission_groups'));
    } 

    // Metodo per aggiornare i permessi di un ruolo
    public function AdminRolesUpdate(Request $request,$id){
        $role = Role::findOrFail($id);
        $permissions = $request->permission;

        if (!empty($permissions)) {
           $role->syncPermissions($permissions);
        }

         $notification = array(
            'message' => 'Role-permission updated',
            'alert-type' => 'success'
        );

        return redirect()->route('roles.permissions')->with($notification); 
    }

    // Metodo per eliminare i permessi di un ruolo
    public function AdminRolesDelete($id){
        $role = Role::findOrFail($id);
        if (!is_null($role)) {
            $role->delete();
        }

        $notification = array(
            'message' => 'Role-permission deleted ',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification); 
    }
}
