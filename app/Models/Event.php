<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 *
 */
class Event extends Model
{

  protected $fillable = ['event_name', 'deskripsi', 'date_start', 'date_end', 'price', 'place',
    'location', 'website', 'phone_number', 'category_id', 'alamat'];
}


 ?>
