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
          $json = ['success' => false, 'status' => 400, 'error' => $validator->errors()];

      }
      else
      {
        // create Event, is_publish = 0 (Karena belum di approve)

          $data = Event::create(['event_name' => $request['event_name'], 'deskripsi' => $request['deskripsi'], 'date_start' => $request['date_start'],
            'date_end' => $request['date_end'], 'price' => $request['price'], 'alamat' => $request['alamat'], 'location' => $request['location'],
            'website' => $request['website'], 'phone_number' => $request['phone_number'],
            'category_id' => $request['category_id'], 'is_publish' => '0']);

          //struktur json
          $json = ['success' => true, 'status' => 200, 'data' => $data];



      }

      return response()->json($json, $json['status']);



    }

    // Parameter : -
    // Method : GET
    public function get_events()
    {


      // Ambil data event join category
      $data = Event::join('categories', 'categories.id', '=', 'events.category_id')->where('is_publish', '1')->get();



      $json = ['success' => true, 'status' => 200, 'data' => $data];
      //struktur json : status (jsonObject), data (jsonArray)
      return response()->json($json, $json['status']);

    }

    // Filter berdasarkan event dan category
    // Parameter : event_name, category_id
    // Method : POST
    public function get_event_filter(Request $request)
    {
        // Jika event_name diisi dan category_id dipilih
        if ($request['keyword'] && $request['category_id'])
        {
            $where = ['category_id' => $request['category_id'], 'keyword' => '%'.$request['keyword'].'%'];
            // Custom Multiple Where
            $events = Event::where(function($query) use ($where)
            {
                $query->where('category_id', '=', $where['category_id']);
                $query->where('event_name', 'like' , $where['keyword']);
            });

        }
        // Jika hanya event_name yang diisi
        else if ($request['keyword'])
        {
            $events = Event::where('event_name', 'like', "%".$request['keyword']."%");
        }
        // Jika hanya category_id yang dipilih
        else if ($request['category_id'])
        {
            $events = Event::where('category_id', $request['category_id']);
        }
        // Jika tak ada yang diisi, menampilkan semua event
        else {
            $events = Event::where('event_name', 'like', '%');
        }

        // get events join categories
        $data = $events->join('categories', 'categories.id', '=', 'events.category_id')->where('is_publish', '1')->get();
        // struktur json
        $json = ['success' => true, 'status' => 200, 'data' => $data];

        return response()->json($json, $json['status']);


    }

    // Parameter : id
    // Method : POST
    public function approve_event(Request $request)
    {
        // authorize approve_event
        $this->authorize('approve_event');

        // membuat validasi id
        $validator = Validator::make($request->all(), [
          'id' => 'required'
        ]);

        // jika gagal validasi
        if ($validator->fails())
        {
            // struktur json
            $json = ['success' => false, 'status' => 400, 'error' => $validator->errors()];

        }
        else
        {
            // Ambil event berdasarkan id
            $event = Event::where('id', $request['id']);
            //check apakah event ada dengan id tersebut
            if ($event->first())
            {
              // update event, return 1/0 (berhasil / tidak)
              $success = $event->update(['is_publish' => '1']);
              // struktur json, dengan data yang telah diupdate
              $json = ['success' => true, 'status' => 200, 'data' => $event->first()];
            }
            else
            {
              $json = ['success' => false, 'status' => 200, 'data' => $event->first()];
            }

        }

        return response()->json($json, $json['status']);
    }


}


 ?>
