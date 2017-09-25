<?php namespace JP_COMMUNITY\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use JP_COMMUNITY\Http\Controllers\BaseController;

class AddressController extends BaseController
{
    protected $checkbox = ['active', 'draft'];
    protected $arrayToJsonField = ['target_page'];
    protected $redirectTo = 'admin/news';
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $resLocations = DB::table('locations')->get();
        $locations = [];
        foreach ($resLocations as $location) {
            if (!empty($location->country_code)) {
                $locations[$location->country_code][] = $location;
            }
        }

        return view('admins.address.index', compact('locations'));
    }

    public function save(Request $request) {
        $this->validate($request, [
            'country_code' => 'required|string|in:vn,ja',
            'city_name' => 'required|string|max:200|min:1',
            'location_id' => 'integer',
            'order' => 'integer',
        ]);
        if (!empty($request->get('location_id'))) {
            // Update
            $res = DB::table('locations')->where('location_id', $request->get('location_id'))->update([
                'country_code' => $request->get('country_code'),
                'order' => $request->get('order'),
                'city_name' => $request->get('city_name')
            ]);
            $resId = $request->get('location_id');
        } else {
            // Create new
            $res = $resId = DB::table('locations')->insertGetId([
                'country_code' => $request->get('country_code'),
                'order' => $request->get('order'),
                'city_name' => $request->get('city_name')
            ]);
        }

        if (!empty($res)) {
            return response()->json([
               'success' => true,
                'data' => [
                    'location_id' => $resId,
                    'country_code' => $request->get('country_code'),
                    'order' => $request->get('order'),
                    'city_name' => $request->get('city_name')
                ]
            ]);
        } else {
            // Case not found
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 403,
                    'message' => trans('message.not_found')
                ]
            ]);
        }
    }
    /**
     * Delete location
     */
    public function delete(Request $request) {
        $this->validate($request, [
            'location_id' => 'required|integer'
        ]);

        $res = DB::table('locations')->where('location_id', $request->get('location_id'))->delete();
        if (!empty($res)) {
            return response()->json([
                'success' => true,
                'data' =>$res
            ]);
        } else {
            return response()->json([
                'success' => false,
                'error' => 403,
                'message' => trans('message.not_found'),
            ]);
        }
    }

    /**
     * Function get location
     * @param Request $request
     * @internal param $country_code
     */
    public function get_location(Request $request) {
        $this->validate($request, [
           'country_code' => 'required|in:vn,ja',
        ]);

        $countryCode = $request->get('country_code');
        $res = DB::table('locations')->where('country_code', $request->get('country_code'))->get();
        $cities = [];
        foreach ($res as $row) {
            $cities[$row->location_id] = $row->city_name;
        }
        return response()->json([
            'success' => true,
            'data' => $cities
        ]);
    }

}
