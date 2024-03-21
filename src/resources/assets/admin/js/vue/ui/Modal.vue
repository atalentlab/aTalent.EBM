<template>
    <div class="modal fade" tabindex="-1" role="dialog" :id=id  :data-loading="isLoading ? 'true' : 'false'">
        <div class="modal-dialog" role="document">
            <div class="loader"></div>
            <div class="modal-content">
                <slot></slot>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'Modal',
        props: ['id', 'isLoading'],
    }
</script>
