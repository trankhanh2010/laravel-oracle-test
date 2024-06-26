<?php

namespace App\Models\HIS;

use App\Traits\dinh_dang_ten_truong;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SereServ extends Model
{
    use HasFactory, dinh_dang_ten_truong;
    protected $connection = 'oracle_his'; 
    protected $table = 'HIS_SERE_SERV';
    protected $fillable = [

    ];
    public function sere_serv_bills()
    {
        return $this->hasMany(SereServBill::class);
    }
    public function sere_serv_debts()
    {
        return $this->hasMany(SereServDebt::class);
    }
    public function sere_serv_deposits()
    {
        return $this->hasMany(SereServDeposit::class);
    }
    public function sere_serv_files()
    {
        return $this->hasMany(SereServFile::class);
    }
    public function sere_serv_matys()
    {
        return $this->hasMany(SereServMaty::class);
    }
    public function sere_serv_pttts()
    {
        return $this->hasMany(SereServPttt::class);
    }
    public function sere_serv_rehas()
    {
        return $this->hasMany(SereServReha::class);
    }
    public function sere_serv_suins()
    {
        return $this->hasMany(SereServSuin::class);
    }
    public function sere_serv_teins()
    {
        return $this->hasMany(SereServTein::class);
    }
    public function service_change_reqs()
    {
        return $this->hasMany(ServiceChangeReq::class);
    }
    public function sese_depo_repays()
    {
        return $this->hasManyThrough(SeseDepoRepay::class, SereServDeposit::class, 'sere_serv_id', 'sere_serv_deposit_id');
    }
    public function sese_trans_reqs()
    {
        return $this->hasMany(SeseTransReq::class);
    }
    public function exp_mest_bloods()
    {
        return $this->hasManyThrough(ExpMestBlood::class, ExpMest::class, 'service_req_id', 'exp_mest_id', 'service_req_id', 'id');
    }
    public function exp_mest_materials()
    {
        return $this->hasManyThrough(ExpMestMaterial::class, ExpMest::class, 'service_req_id', 'exp_mest_id', 'service_req_id', 'id');
    }
    public function exp_mest_medicines()
    {
        return $this->hasManyThrough(ExpMestMedicine::class, ExpMest::class, 'service_req_id', 'exp_mest_id', 'service_req_id', 'id');
    }
}
