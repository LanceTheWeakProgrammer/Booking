<!-- Modal for cancel booking -->
<div class="modal fade" id="cancelBookingModal{{ $booking->id }}" tabindex="-1" aria-labelledby="cancelBookingModalLabel{{ $booking->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancelBookingModalLabel">Cancel Booking</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to cancel this booking?</p>
                <div class="mb-3">
                    <label for="cancelReason" class="form-label">Reason for cancellation:</label>
                    <textarea class="form-control" id="cancelReason" rows="3"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn custom-bg text-white" onclick="requestCancelBooking({{ $booking->id }})" data-bs-dismiss="modal" id="cancelButton{{ $booking->id }}">Request Cancel Booking</button>
            </div>
        </div>
    </div>
</div>
