<?php
namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Models\Event;
use Validator;
/**
 *
 */
class EventController extends BaseController
{

    // Parameter : event_name, deskripsi, date_start, date_end, price, alamat, location, website, phone_number,
      // category_id
    // Method : POST
    public function create_event(Request $request)
    {

      // membuat validasi
      $validator = Validator::make($request->all(), [
        'event_name' => 'required',
        'deskripsi' => 'required',
        'date_start' => 'required|date',
        'date_end' => 'required|date',
        'price' => 'required|Integer',
        'alamat' => 'required',
        'location' => 'required',
        'website' => 'required',
        'phone_number' => 'required|min:11|max:13',
        'category_id' => 'required'
      ]);

      if ($validator->fails())
      {
          // struktur json
          $json = ['status' => '0', 'error' => $validator->errors()];
          // HTTP code
          $code = 400;
      }
      else
      {
        // create Event

          $data = Event::create(['event_name' => $request['event_name'], 'deskripsi' => $request['deskripsi'], 'date_start' => $request['date_start'],
            'date_end' => $request['date_end'], 'price' => $request['price'], 'alamat' => $request['alamat'], 'location' => $request['location'],
            'website' => $request['website'], 'phone_number' => $request['phone_number'], 'category_id' => $request['category_id']]);

          //struktur json
          $json = ['status' => '1', 'data' => $data];
          //HTTP code
          $code = 200;


      }

      return response()->json($json, $code);



    }

    // Parameter : -
    // Method : GET
    public function get_events()
    {


      // Ambil data event join category
      $data = Event::join('categories', 'categories.id', '=', 'events.category_id')->get();

      if (isset($data[0]))
      {
        // jika indeks ke-0 array data, status = 1 (true)
        $status = '1';
      }
      else {
        //status = 0 (false)
        $status = '0';
      }

      $json = ['status' => $status, 'data' => $data];
      //struktur json : status (jsonObject), data (jsonArray)
      return response()->json($json);

    }


}


 ?>
