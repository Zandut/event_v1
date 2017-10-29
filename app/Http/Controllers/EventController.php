<?php
namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

use App\Models\Event;
/**
 *
 */
class EventController extends BaseController
{
    public function get_events()
    {
      # code...
      $data = Event::join('categories', 'categories.id', '=', 'events.category_id')->get();
      if (isset($data[0]))
      {
        $status = '1';
      }
      else {
        $status = '0';
      }

      return response()->json(['status' => $status, 'data' => $data]);

    }
}


 ?>
