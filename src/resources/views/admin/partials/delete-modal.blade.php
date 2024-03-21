<div class="modal fade" id="delete-modal" tabindex="-1" role="dialog" aria-labelledby="delete-modal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form class="js-form" method="POST" action="{{ $action ?? '' }}">
                @csrf
                <input type="hidden" name="_method" value="DELETE">

                <div class="modal-header">
                    <h5 class="modal-title">Delete</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to permanently delete this?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary mr-auto" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger js-submit" data-loading='<i class="fe fe-loader mr-2"></i> Deleting...'>Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>
