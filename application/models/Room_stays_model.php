<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Room_stays_model extends CI_Model
{
    public function getRoomsForCheckin($date)
    {
        $rooms = $this->db->where('rmStatus', 1)->order_by('rmID', 'ASC')->get('tbl_rooms')->result();
        foreach ($rooms as $room) {
            // A booking occupies every date in its stored range, not only its start date.
            // POS date ranges are inclusive: Nov 4-7 with ckDays=4 occupies Nov 4, 5, 6 and 7.
            $dateSql = $this->db->escape($date);
            $room->reservation = $this->db->where('ckRoomID', $room->rmID)
                ->where('ckCheckInType', 2)
                ->where('ckDate <=', $date)
                ->where("DATE_ADD(ckDate, INTERVAL GREATEST(IFNULL(NULLIF(ckDays, 0), 1), 1) DAY) > {$dateSql}", null, false)
                ->get('tbl_checkin')->row();
            $room->booking_for_date = $this->db->where('ckRoomID', $room->rmID)
                ->where_in('ckCheckInType', array(1, 2, 3))
                ->where('ckDate <=', $date)
                ->where("DATE_ADD(ckDate, INTERVAL GREATEST(IFNULL(NULLIF(ckDays, 0), 1), 1) DAY) > {$dateSql}", null, false)
                ->get('tbl_checkin')->row();
            $room->active_stay = $this->getActiveByRoom($room->rmID);
        }
        return $rooms;
    }

    public function getStaysCheckedInOn($date)
    {
        return $this->db->select('s.*, r.rmTitle, r.rmCapacity, r.rmPrice')
            ->from('tbl_room_stays s')
            ->join('tbl_rooms r', 'r.rmID = s.stayRoomID', 'left')
            ->where('DATE(s.stayTimeIn) = '.$this->db->escape($date), null, false)
            ->order_by('s.stayTimeIn', 'DESC')->get()->result();
    }

    public function getActiveByRoom($roomID)
    {
        return $this->db->where('stayRoomID', (int)$roomID)->where('stayStatus', 'active')->get('tbl_room_stays')->row();
    }

    public function getStay($id)
    {
        return $this->db->where('stayID', (int)$id)->get('tbl_room_stays')->row();
    }

    public function checkin($roomID, $reservationID, $guestName, $roomPrice, $discount, $otherCharges, $payment, $paymentType, $userID, $fixDiscount = 0, $roomDiscountID = null, $percentDiscountAmount = null, $finalPrice = null)
    {
        $this->db->trans_begin();
        if ($this->getActiveByRoom($roomID)) { $this->db->trans_rollback(); return array(false, 'This room is already occupied.'); }
        $total = $finalPrice === null ? round((float)$roomPrice - (float)$discount + (float)$otherCharges, 2) : round((float)$finalPrice, 2);
        if ((float)$payment < $total) { $this->db->trans_rollback(); return array(false, 'Payment must cover the room amount due.'); }
        $now = date('Y-m-d H:i:s');
        $checkinData = array(
            'ckRoomID'=>(int)$roomID, 'ckDate'=>date('Y-m-d'), 'ckPaymentStatus'=>null,
            'ckGuestName'=>trim($guestName), 'ckOtherCharges'=>round((float)$otherCharges,2),
            'ckFixDiscount'=>round((float)$fixDiscount,2), 'ckRoomDiscountID'=>$roomDiscountID ?: null,
            'ckPrice'=>$total, 'ckDiscountAmount'=>round((float)$discount,2),
            'ckCheckInType'=>1, 'ckPaymentType'=>$paymentType
        );
        if ($reservationID) {
            $existing = $this->db->where('ckID',(int)$reservationID)->get('tbl_checkin')->row();
            if (!$existing) { $this->db->trans_rollback(); return array(false, 'The reservation could not be found.'); }
            $checkinData['ckDateCreated'] = $existing->ckDateCreated ?: $now;
            $checkinData['ckReservationDate'] = $existing->ckReservationDate;
            $this->db->where('ckID',(int)$reservationID)->update('tbl_checkin',$checkinData);
            $checkinRecordID = (int)$reservationID;
        } else {
            $checkinData['ckDateCreated'] = $now;
            $checkinData['ckReservationDate'] = null;
            $this->db->insert('tbl_checkin',$checkinData);
            $checkinRecordID = (int)$this->db->insert_id();
        }
        if (!$checkinRecordID) { $this->db->trans_rollback(); return array(false, 'Unable to save check-in record.'); }
        $this->db->insert('tbl_room_stays', array(
            'stayRoomID'=>(int)$roomID, 'stayReservationID'=>$checkinRecordID,
            'stayGuestName'=>trim($guestName), 'stayDate'=>date('Y-m-d'), 'stayTimeIn'=>$now,
            'stayRoomPrice'=>round((float)$roomPrice,2), 'stayRoomDiscount'=>round((float)$discount,2),
            'stayOtherCharges'=>round((float)$otherCharges,2), 'stayRoomPayment'=>round((float)$payment,2),
            'stayRoomChange'=>round((float)$payment-$total,2), 'stayPaymentType'=>$paymentType,
            'stayStatus'=>'active', 'stayCreatedBy'=>(int)$userID
        ));
        $id = (int)$this->db->insert_id();
        if ($this->db->trans_status() === false || !$id) { $this->db->trans_rollback(); return array(false, 'Unable to save check-in.'); }
        $this->db->trans_commit();
        return array($id, null);
    }

    public function getOrders($stayID)
    {
        return $this->db->where('rsoStayID', (int)$stayID)->order_by('rsoID', 'ASC')->get('tbl_room_stay_orders')->result();
    }

    public function addOrder($stayID, $product, $qty, $userID)
    {
        $stay = $this->getStay($stayID);
        if (!$stay || $stay->stayStatus !== 'active') return array(false, 'Room stay is not active.');
        $product = $this->db->where('prodID', (int)$product)->where('prodStatus', 1)->get('tbl_products')->row();
        if (!$product) return array(false, 'Product was not found or is disabled.');
        $qty = (int)$qty;
        if ($qty < 1 || $qty > 999) return array(false, 'Quantity must be between 1 and 999.');
        $price = (float)$product->prodPrice;
        $this->db->insert('tbl_room_stay_orders', array(
            'rsoStayID'=>(int)$stayID, 'rsoProductID'=>(int)$product->prodID, 'rsoProductName'=>$product->prodName,
            'rsoQty'=>$qty, 'rsoUnitPrice'=>$price, 'rsoLineTotal'=>round($price*$qty,2),
            'rsoCreatedBy'=>(int)$userID, 'rsoCreatedAt'=>date('Y-m-d H:i:s')
        ));
        return $this->db->affected_rows() > 0 ? array(true, null) : array(false, 'Unable to add item.');
    }

    public function checkout($stayID, $payment, $userID)
    {
        $stay = $this->getStay($stayID);
        if (!$stay || $stay->stayStatus !== 'active') return array(false, 'This stay is already checked out or unavailable.');
        $orders = $this->getOrders($stayID); $foodTotal = 0;
        foreach ($orders as $item) $foodTotal += (float)$item->rsoLineTotal;
        $roomDue = max(0, (float)$stay->stayRoomPrice - (float)$stay->stayRoomDiscount + (float)$stay->stayOtherCharges);
        $grandTotal = round($roomDue + $foodTotal, 2);
        // Room fee was collected at check-in; only restaurant orders remain due at checkout.
        $roomPaid = (float)$stay->stayRoomPayment - (float)$stay->stayRoomChange;
        $balance = max(0, round($grandTotal - $roomPaid, 2));
        if ((float)$payment < $balance) return array(false, 'Checkout payment must cover the remaining balance of '.number_format($balance,2).'.');
        $this->db->trans_begin();
        $this->db->where('stayID', (int)$stayID)->where('stayStatus', 'active')->update('tbl_room_stays', array(
            'stayTimeOut'=>date('Y-m-d H:i:s'), 'stayStatus'=>'checked_out', 'stayCheckoutTotal'=>$grandTotal,
            'stayCheckoutPayment'=>round((float)$payment,2), 'stayCheckoutChange'=>round((float)$payment-$balance,2), 'stayCheckoutBy'=>(int)$userID
        ));
        if ($this->db->affected_rows() > 0 && !empty($stay->stayReservationID)) {
            $this->db->where('ckID',(int)$stay->stayReservationID)->update('tbl_checkin',array('ckCheckInType'=>3));
        }
        if ($this->db->trans_status() === false) { $this->db->trans_rollback(); return array(false, 'Could not complete checkout.'); }
        $this->db->trans_commit();
        return array(true, null);
    }

    public function getAttendanceReport($from, $to, $userID = null)
    {
        $this->db->select('s.*, r.rmTitle, u.urName, u.urLname');
        $this->db->from('tbl_room_stays s')->join('tbl_rooms r','r.rmID=s.stayRoomID','left')->join('tbl_users u','u.urID=s.stayCreatedBy','left');
        $this->db->where('s.stayDate >=', $from)->where('s.stayDate <=', $to);
        if ($userID) $this->db->where('s.stayCreatedBy', (int)$userID);
        $this->db->order_by('s.stayDate','DESC')->order_by('s.stayTimeIn','DESC');
        return $this->db->get()->result();
    }
}
