<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\GuestModel;

class GuestApi extends BaseController
{
    protected $guestModel;

    public function __construct()
    {
        $this->guestModel = new GuestModel();
    }

    /**
     * GET /api/guest/{device_id}
     */
    public function byDevice($device_id)
    {
        if (!$device_id) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Device ID is required'
            ])->setStatusCode(400);
        }

        $guest = $this->guestModel
            ->where('device_id', $device_id)
            ->where('status', 'ACTIVE')
            ->where('checkout_date >=', date('Y-m-d'))
            ->first();

        if (!$guest) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Guest not found or already checkout'
            ])->setStatusCode(404);
        }

        return $this->response->setJSON([
            'status' => true,
            'data' => [
                'id'            => $guest['id'],
                'guest_name'    => $guest['guest_name'],
                'room_no'       => $guest['room_no'],
                'device_id'     => $guest['device_id'],
                'checkin_date'  => $guest['checkin_date'],
                'checkout_date' => $guest['checkout_date'],
                'status'        => $guest['status']
            ]
        ]);
    }

    public function byRoom($room_no)
	{
	    if (!$room_no) {
	        return $this->response->setJSON([
	            'status'  => false,
	            'message' => 'Room number is required'
	        ])->setStatusCode(400);
	    }

	    $guest = $this->guestModel
	        ->where('room_no', $room_no)
	        ->where('status', 'ACTIVE')
	        ->where('checkout_date >=', date('Y-m-d'))
	        ->first();

	    if (!$guest) {
	        return $this->response->setJSON([
	            'status'  => false,
	            'message' => 'Guest not found or already checkout'
	        ])->setStatusCode(404);
	    }

	    return $this->response->setJSON([
	        'status' => true,
	        'data' => [
	            'id'            => $guest['id'],
	            'guest_name'    => $guest['guest_name'],
	            'room_no'       => $guest['room_no'],
	            'device_id'     => $guest['device_id'],
	            'checkin_date'  => $guest['checkin_date'],
	            'checkout_date' => $guest['checkout_date'],
	            'status'        => $guest['status']
	        ]
	    ]);
	}

}
