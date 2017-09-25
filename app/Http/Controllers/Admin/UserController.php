<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 1/11/2017
 * Time: 8:39 AM
 */

namespace JP_COMMUNITY\Http\Controllers\Admin;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use JP_COMMUNITY\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use JP_COMMUNITY\Models\User;

class UserController extends BaseController
{

    protected $redirectTo = 'admin/users';
    protected $checkbox = ['active'];
    /**
     * List of admin user
     */
    public function index() {
        $users = User::withoutGlobalScope('active')->paginate(10);
        return view('admins.users.index', compact('users'));
    }

    /**
     * Detail of user
     * @param int $id
     */
    public function show($id) {

    }

    /**
     * Edit function
     * @param int $id User ID
     */
    public function edit($id) {
        $user = User::withoutGlobalScope('active')->where('id', $id)->first();

        $user_type = User::userType()['admin'];

        return view('admins.users.edit', compact(['user', 'user_type']));
    }
    /**
     * Create user
     *
     */
    public function create() {
        $user_type = User::userType()['admin'];
        return view('admins.users.create', compact('user_type'));
    }
    /**
     * Storage user
     */
    public function store(Request $request) {

        $request = $this->prepareFormInput($request);

        $this->validate($request, [
            'user_type' => "required|user_role_admin",
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|max:250|confirmed',
            'active' => 'boolean'
        ]);

        $request->merge(['password' => bcrypt($request->get('password'))]);

        $res_user = User::create([
            'user_type' => $request->get('user_type'),
            'email' => $request->get('email'),
            'password' => $request->get('password'),
            'active' => $request->get('active')
        ]);
         return redirect($this->redirectTo);

    }

    /**
     * Update function
     */
    public function update(Request $request) {

        $request = $this->prepareFormInput($request);

        $this->validate($request, [
            'user_type' => "required|user_role_admin",
            'email' => "required|email|max:255|unique:users,email," . $request->get('user_id'),
            'password' => 'min:6|max:250|confirmed',
            'active' => 'boolean'
        ]);

//        $request->merge(['password' => bcrypt($request->get('password'))]);

        $res = $res_user = User::withoutGlobalScope('active')->where('id', $request->get('user_id'))->first();

        $res_user->update([
            'user_type' => $request->get('user_type'),
            'email' => $request->get('email'),
            'password' => !empty($request->get('password')) ? bcrypt($request->get('password')) : $res->password,
            'active' => $request->get('active')
        ]);

        return redirect($this->redirectTo);
    }

    /**
     * Function destroy
     */
    public function destroy(Request $request) {
        if ($request->ajax()) {
            $ids = Input::get('user_ids');
            if (!empty($ids)) {
                if(!is_array($ids)) {
                    $ids = (array)$ids;
                }
            } else {
                return ;
            }

            if (($index = array_search(Auth::id(), $ids)) || $index === 0) {
                unset($ids[$index]);
            }

            if (empty($ids)) {
                return ;
            }

            DB::beginTransaction();
                try{
                    DB::table('customer')->whereIn('user_id', $ids)->update(['deleted_at' => Carbon::now()]);
                    DB::table('client')->whereIn('user_id', $ids)->update(['deleted_at' => Carbon::now()]);
                    DB::table('users')->whereIn('id', $ids)->update(['deleted_at' => Carbon::now()]);
                } catch (Exeption $e) {
                    DB::rollBack();
                }
            DB::commit();
        }
    }

}