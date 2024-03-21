<div class="modal fade" id="confirmation-modal" tabindex="-1" role="dialog" aria-labelledby="confirmation-modal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>{{ $message }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary mr-auto" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary js-confirm" data-target="{{ $target ?? '.js-form' }}" data-loading='<i class="fe fe-loader mr-2"></i> Saving...'>Confirm</button>
            </div>
        </div>
    </div>
</div>
