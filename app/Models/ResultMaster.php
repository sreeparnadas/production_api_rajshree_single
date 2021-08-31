<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResultMaster extends Model
{
    use HasFactory;

    /**
     * @var mixed
     */
    private $draw_master_id;

    /**
     * @var \Illuminate\Support\Carbon|mixed
     */
    private $game_date;
    /**
     * @var mixed
     */
    private $single_number_id;
}
