<div class="modal fade" id="editGuestModal">
    <div class="modal-dialog modal-lg">
        <form id="editGuestForm">
            <?= csrf_field() ?>
            <input type="hidden" name="id" id="edit_id">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Guest</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="guest_name">Guest Name</label>
                                <input type="text" name="guest_name" id="edit_guest_name" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="device_id">Room No.</label>
                                <select name="room_no" id="edit_room_no" class="form-control select2" style="width: 100%;" required>
        							<option value="">-- Select Room --</option>
    							</select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="device_id">Device TV</label>
                                <input type="text" name="device_id" id="edit_device_id" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select name="status" id="edit_status" class="form-control">
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
                                <input type="date" name="checkin_date" id="edit_checkin_date" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="checkout_date">Check-out</label>
                                <input type="date" name="checkout_date" id="edit_checkout_date" class="form-control" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-warning">Update</button>
                    <button class="btn btn-secondary" data-dismiss="modal" type="button">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>
