<?php

namespace App\Controllers;

use App\Models\User;
use App\Controllers\Controller;
use App\Models\Bill;
use App\Models\BillDetail;
use App\SessionGuard as Guard;

class BillController extends Controller
{
    public function payHistory(){
		$khach = User::where('email', Guard::user()->email)->first();
		$this->sendPage('bill/payHistory',['bills' => Bill::where('id', $khach->id)->orderBy('ngay_lap', 'DESC')->get()]);
	}

	public function detailBill(){
		$this->sendPage('bill/detailBill', ['bill' =>BillDetail::join('sach', 'sach.ma_sach', '=', 'chitiethoadon.ma_sach')->where('ma_hoa_don', $_GET['mhd'])->get(),
	                                         'billdetail' => Bill::where('ma_hoa_don', $_GET['mhd'])->get()]);
	}
    
	public function cancel($billId)
    {
        $data['trang_thai'] = "Canceled";
        $bill = Bill::where('ma_hoa_don', '=', $billId)->first();
        $bill->update($data);
        redirect('../payHistory');
    }

	public function received($billId)
    {
        $data['trang_thai'] = "received";
        $bill = Bill::where('ma_hoa_don', '=', $billId)->first();
        $bill->update([
			'trang_thai' => "recieved",
			'trang_thai_thanh_toan' => "Đã thanh toán" ]);
        redirect('../payHistory');
    }
}