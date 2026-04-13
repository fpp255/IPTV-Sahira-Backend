<div class="modal fade" id="addGuestModal">
    <div class="modal-dialog modal-lg">
        <form id="addGuestForm">
            <?= csrf_field() ?>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Guest</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="guest_name">Guest Name</label>
                                <input type="text" name="guest_name" id="guest_name" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="device_id">Room No.</label>
                                <select name="room_no" id="room_no" class="form-control select2" style="width: 100%;" required>
                                    <option value="">-- Select Room --</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="device_id">Device TV</label>
                                <input type="text" name="device_id" id="device_id" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="ACTIVE">ACTIVE</option>
                                    <option value="CHECKOUT">CHECKOUT</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="checkin_date">Check-in</label>
                                <input type="date" name="checkin_date" id="checkin_date" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="checkout_date">Check-out</label>
                                <input type="date" name="checkout_date" id="checkout_date" class="form-control" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary">Save</button>
                    <button class="btn btn-secondary" data-dismiss="modal" type="button">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>
