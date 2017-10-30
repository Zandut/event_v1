<?php
namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

use App\Models\Event;
/**
 *
 */
class EventController extends BaseController
{

  public function create_event(Request $request)
  {
    # code...
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
