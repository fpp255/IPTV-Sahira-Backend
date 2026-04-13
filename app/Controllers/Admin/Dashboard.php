<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\Exceptions\PageForbiddenException;
use Config\Database;

class Dashboard extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function index()
    {
        // ROLE ADMIN (DEFAULT)
        if (can(['admin'])) {
            /** 
             * ============================
             * ROOM COUNTERS
             * ============================
             */

            // Total rooms
            $totalRooms = $this->db->table('tv_devices')
                ->where('deleted_at', null)
                ->countAllResults();

            // Occupied rooms (ACTIVE)
            $occupiedRooms = $this->db->table('guests')
                ->where('status', 'ACTIVE')
                ->where('deleted_at', null)
                ->countAllResults();

            // Available rooms
            $availableRooms = $totalRooms - $occupiedRooms;

            // Check-in today
            $checkinToday = $this->db->table('guests')
                ->where('DATE(checkin_date)', date('Y-m-d'))
                ->where('deleted_at', null)
                ->countAllResults();

            // Check-out today
            $checkoutToday = $this->db->table('guests')
                ->where('DATE(checkout_date)', date('Y-m-d'))
                ->where('status', 'ACTIVE')
                ->where('deleted_at', null)
                ->countAllResults();

            // Overdue checkout
            $overdueCheckout = $this->db->table('guests')
                ->where('checkout_date <', date('Y-m-d'))
                ->where('status', 'ACTIVE')
                ->where('deleted_at', null)
                ->countAllResults();

            // Check-out today List
            $todayCheckouts = $this->db->query("
                SELECT
                    id,
                    room_no,
                    guest_name,
                    status,
                    checkout_date
                FROM guests
                WHERE status = 'ACTIVE'
                  AND checkout_date IS NOT NULL
                  AND DATE(checkout_date) = CURDATE()
                  AND deleted_at IS NULL
                ORDER BY checkout_date ASC
            ")->getResultArray();

            // Overdue List
            $lateCheckouts = $this->db->query("
                SELECT 
                    id,
                    room_no,
                    guest_name,
                    status,
                    checkout_date,
                    DATEDIFF(CURDATE(), DATE(checkout_date)) AS late_days
                FROM guests
                WHERE status = 'ACTIVE'
                  AND checkout_date IS NOT NULL
                  AND DATE(checkout_date) < CURDATE()
                  AND deleted_at IS NULL
                ORDER BY checkout_date ASC
            ")->getResultArray();

            return view('admin/dashboard', [
                'title' => 'Dashboard',
                'availableRooms' => $availableRooms,
                'occupiedRooms'  => $occupiedRooms,
                'checkinToday'   => $checkinToday,
                'checkoutToday'  => $checkoutToday,
                'overdueCheckout'=> $overdueCheckout,
                'todayCheckouts' => $todayCheckouts,
                'lateCheckouts'  => $lateCheckouts
            ]);
        }

        // ROLE FnB
        if (can('fnb')) {
            return view('admin/dashboardfnb', [
                'title' => 'Dashboard FnB'
            ]);
        }

        // ROLE FO
        if (can('fo')) {
            /** 
             * ============================
             * ROOM COUNTERS
             * ============================
             */

            // Total rooms
            $totalRooms = $this->db->table('tv_devices')
                ->where('deleted_at', null)
                ->countAllResults();

            // Occupied rooms (ACTIVE)
            $occupiedRooms = $this->db->table('guests')
                ->where('status', 'ACTIVE')
                ->where('deleted_at', null)
                ->countAllResults();

            // Available rooms
            $availableRooms = $totalRooms - $occupiedRooms;

            // Check-in today
            $checkinToday = $this->db->table('guests')
                ->where('DATE(checkin_date)', date('Y-m-d'))
                ->where('deleted_at', null)
                ->countAllResults();

            // Check-out today
            $checkoutToday = $this->db->table('guests')
                ->where('DATE(checkout_date)', date('Y-m-d'))
                ->where('status', 'ACTIVE')
                ->where('deleted_at', null)
                ->countAllResults();

            // Overdue checkout
            $overdueCheckout = $this->db->table('guests')
                ->where('checkout_date <', date('Y-m-d'))
                ->where('status', 'ACTIVE')
                ->where('deleted_at', null)
                ->countAllResults();

            // Check-out today List
            $todayCheckouts = $this->db->query("
                SELECT
                    id,
                    room_no,
                    guest_name,
                    status,
                    checkout_date
                FROM guests
                WHERE status = 'ACTIVE'
                  AND checkout_date IS NOT NULL
                  AND DATE(checkout_date) = CURDATE()
                  AND deleted_at IS NULL
                ORDER BY checkout_date ASC
            ")->getResultArray();

            // Overdue List
            $lateCheckouts = $this->db->query("
                SELECT 
                    id,
                    room_no,
                    guest_name,
                    status,
                    checkout_date,
                    DATEDIFF(CURDATE(), DATE(checkout_date)) AS late_days
                FROM guests
                WHERE status = 'ACTIVE'
                  AND checkout_date IS NOT NULL
                  AND DATE(checkout_date) < CURDATE()
                  AND deleted_at IS NULL
                ORDER BY checkout_date ASC
            ")->getResultArray();

            
            return view('admin/dashboardfo', [
                'title'          => 'Dashboard FO',
                'availableRooms' => $availableRooms,
                'occupiedRooms'  => $occupiedRooms,
                'checkinToday'   => $checkinToday,
                'checkoutToday'  => $checkoutToday,
                'overdueCheckout'=> $overdueCheckout,
                'todayCheckouts' => $todayCheckouts,
                'lateCheckouts'  => $lateCheckouts
            ]);
        }

        // ROLE HK
        if (can('hk')) {
            /** 
             * ============================
             * ROOM COUNTERS
             * ============================
             */

            // Total rooms
            $totalRooms = $this->db->table('tv_devices')
                ->where('deleted_at', null)
                ->countAllResults();

            // Occupied rooms (ACTIVE)
            $occupiedRooms = $this->db->table('guests')
                ->where('status', 'ACTIVE')
                ->where('deleted_at', null)
                ->countAllResults();

            // Available rooms
            $availableRooms = $totalRooms - $occupiedRooms;

            // Check-in today
            $checkinToday = $this->db->table('guests')
                ->where('DATE(checkin_date)', date('Y-m-d'))
                ->where('deleted_at', null)
                ->countAllResults();

            // Check-out today
            $checkoutToday = $this->db->table('guests')
                ->where('DATE(checkout_date)', date('Y-m-d'))
                ->where('status', 'ACTIVE')
                ->where('deleted_at', null)
                ->countAllResults();

            // Overdue checkout
            $overdueCheckout = $this->db->table('guests')
                ->where('checkout_date <', date('Y-m-d'))
                ->where('status', 'ACTIVE')
                ->where('deleted_at', null)
                ->countAllResults();

            // Check-out today List
            $todayCheckouts = $this->db->query("
                SELECT
                    id,
                    room_no,
                    guest_name,
                    status,
                    checkout_date
                FROM guests
                WHERE status = 'ACTIVE'
                  AND checkout_date IS NOT NULL
                  AND DATE(checkout_date) = CURDATE()
                  AND deleted_at IS NULL
                ORDER BY checkout_date ASC
            ")->getResultArray();

            // Overdue List
            $lateCheckouts = $this->db->query("
                SELECT 
                    id,
                    room_no,
                    guest_name,
                    status,
                    checkout_date,
                    DATEDIFF(CURDATE(), DATE(checkout_date)) AS late_days
                FROM guests
                WHERE status = 'ACTIVE'
                  AND checkout_date IS NOT NULL
                  AND DATE(checkout_date) < CURDATE()
                  AND deleted_at IS NULL
                ORDER BY checkout_date ASC
            ")->getResultArray();

            
            return view('admin/dashboardhk', [
                'title'          => 'Dashboard HK',
                'availableRooms' => $availableRooms,
                'occupiedRooms'  => $occupiedRooms,
                'checkinToday'   => $checkinToday,
                'checkoutToday'  => $checkoutToday,
                'overdueCheckout'=> $overdueCheckout,
                'todayCheckouts' => $todayCheckouts,
                'lateCheckouts'  => $lateCheckouts
            ]);
        }

        // ROLE ENG
        if (can('eng')) {
            return view('admin/dashboardeng', [
                'title' => 'Dashboard Eng'
            ]);
        }

        // ROLE IT
        if (can('it')) {
            /** 
             * ============================
             * ROOM COUNTERS
             * ============================
             */

            // Total rooms
            $totalRooms = $this->db->table('tv_devices')
                ->where('deleted_at', null)
                ->countAllResults();

            // Occupied rooms (ACTIVE)
            $occupiedRooms = $this->db->table('guests')
                ->where('status', 'ACTIVE')
                ->where('deleted_at', null)
                ->countAllResults();

            // Available rooms
            $availableRooms = $totalRooms - $occupiedRooms;

            // Check-in today
            $checkinToday = $this->db->table('guests')
                ->where('DATE(checkin_date)', date('Y-m-d'))
                ->where('deleted_at', null)
                ->countAllResults();

            // Check-out today
            $checkoutToday = $this->db->table('guests')
                ->where('DATE(checkout_date)', date('Y-m-d'))
                ->where('status', 'ACTIVE')
                ->where('deleted_at', null)
                ->countAllResults();

            // Overdue checkout
            $overdueCheckout = $this->db->table('guests')
                ->where('checkout_date <', date('Y-m-d'))
                ->where('status', 'ACTIVE')
                ->where('deleted_at', null)
                ->countAllResults();

            // Check-out today List
            $todayCheckouts = $this->db->query("
                SELECT
                    id,
                    room_no,
                    guest_name,
                    status,
                    checkout_date
                FROM guests
                WHERE status = 'ACTIVE'
                  AND checkout_date IS NOT NULL
                  AND DATE(checkout_date) = CURDATE()
                  AND deleted_at IS NULL
                ORDER BY checkout_date ASC
            ")->getResultArray();

            // Overdue List
            $lateCheckouts = $this->db->query("
                SELECT 
                    id,
                    room_no,
                    guest_name,
                    status,
                    checkout_date,
                    DATEDIFF(CURDATE(), DATE(checkout_date)) AS late_days
                FROM guests
                WHERE status = 'ACTIVE'
                  AND checkout_date IS NOT NULL
                  AND DATE(checkout_date) < CURDATE()
                  AND deleted_at IS NULL
                ORDER BY checkout_date ASC
            ")->getResultArray();

            
            return view('admin/dashboardhk', [
                'title'          => 'Dashboard IT',
                'availableRooms' => $availableRooms,
                'occupiedRooms'  => $occupiedRooms,
                'checkinToday'   => $checkinToday,
                'checkoutToday'  => $checkoutToday,
                'overdueCheckout'=> $overdueCheckout,
                'todayCheckouts' => $todayCheckouts,
                'lateCheckouts'  => $lateCheckouts
            ]);;
        }

        // ROLE LAIN TIDAK BOLEH AKSES
        throw new PageForbiddenException();
    }
}
