<?php

namespace App\Models\HIS;

use App\Traits\dinh_dang_ten_truong;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExroRoom extends Model
{
    use HasFactory, dinh_dang_ten_truong;
    protected $connection = 'oracle_his'; 
    protected $table = 'HIS_EXRO_ROOM';
    protected $fillable = [

    ];
    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    public function execute_room()
    {
        return $this->belongsTo(ExecuteRoom::class, 'execute_room_id');
    }
}
