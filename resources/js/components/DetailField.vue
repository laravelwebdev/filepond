<template>

    <PanelItem :index="index" :field="field">

        <template #value>

            <FilePond
                :disabled="true"
                :allow-reorder="false"
                :allow-remove="false"
                :limit="field.value.length"
                :field="field"/>

        </template>

    </PanelItem>

</template>

<script>

    import FilePond from './FilePond'

    export default {
        components: { FilePond },
        props: [ 'index', 'resource', 'resourceName', 'resourceId', 'field' ],
    }

</script>