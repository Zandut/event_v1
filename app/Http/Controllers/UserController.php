<?php
namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Validator;
use App\Models\User;

/**
 *
 */
class UserController extends BaseController
{
    // Method : post
    // Parameter : email, password, user_name, role_id
    public function create_user(Request $request)
    {

      //authorize create_user
      $this->authorize('create_user');

      //membuat validasi input request
      $validator = Validator::make($request->all(), [
        'email' => 'required|email',
        'password' => 'required',
        'user_name' => 'required',
        'role_id' => 'required'
      ]);

      // jika validasi gagal
      if ($validator->fails())
      {


        //struktur json

        $json = ['success' => false, 'status' => 400, 'error' => $validator->errors()];

      }
      else
      {
        //api_key random 40 char
        $apikey = base64_encode(str_random(40));

          //create User
          $data = User::create(['email' => $request['email'], 'password' => md5($request['password']),
            'user_name' => $request['user_name'], 'role_id' => $request['role_id'], 'api_key' => $apikey]);

          //struktur json
          $json = ['success' => true, 'status' => 200, 'api_key' => $apikey];




      }

      return response()->json($json, $json['status']);
    }

    // Method : post
    // Parameter : email, password, user_name
    public function register(Request $request)
    {


      //membuat validasi input request
      $validator = Validator::make($request->all(), [
        'email' => 'required|email',
        'password' => 'required',
        'user_name' => 'required',

      ]);

      // jika validasi gagal
      if ($validator->fails())
      {


        //struktur json

        $json = ['success' => false, 'status' => 400, 'error' => $validator->errors()];

      }
      else
      {
        //api_key random 40 char
        $apikey = base64_encode(str_random(40));

          //create User, role_id = 2 Karena yang registrasi User
          $data = User::create(['email' => $request['email'], 'password' => md5($request['password']),
            'user_name' => $request['user_name'], 'role_id' => '2', 'api_key' => $apikey]);

          //struktur json
          $json = ['success' => true, 'status' => 200, 'api_key' => $apikey];


      }

      return response()->json($json, $json['status']);
    }


    // Method : POST
    // Parameter : email, password
    public function login(Request $request)
    {
      # code...

      // membuat validasi request input
      $validator = Validator::make($request->all(), [
        'email' => 'required|email',
        'password' => 'required'
      ]);

      // jika validasi gagal
      if ($validator->fails())
      {

        // struktur json

        $json = ['success' => false, 'status' => 400, 'error' => $validator->errors()];

      }
      else
      {
        // api_key random 40 char
        $apikey = base64_encode(str_random(40));

          //update api_key agar selalu berubah key untuk keamanan
          $data = User::where(['email' => $request['email'], 'password' => md5($request['password'])])->update(['api_key'=> $apikey]);

          // struktur json
          $json = ['success' => true, 'status' => 200, 'api_key' => $apikey];


      }



      return response()->json($json, $json['status']);

    }

}


 ?>
