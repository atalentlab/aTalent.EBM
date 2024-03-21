

export function init() {
    $('#delete-modal').on('show.bs.modal', (e) => {
        let $trigger = $(e.relatedTarget);
        let $modal = $(e.currentTarget);

        if ($trigger.data('delete-url')) {
            $modal.find('form').prop('action', $trigger.data('delete-url'));
        }
    });
}
