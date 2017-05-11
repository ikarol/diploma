<template>
    <tr>
        <td><slot name="col-topic"></slot></td>
        <td><slot name="col-requests-accepted"></slot></td>
        <td><slot name="col-requests-pending"></slot></td>
        <td><slot name="col-requests-denied"></slot></td>
        <td><slot name="col-student"></slot></td>
        <td><slot name="col-cr_at"></slot></td>
        <td><slot name="col-actions"></slot></td>
    </tr>
</template>

<script>
    export default {
        mounted() {
            console.log('task row mounted');
        }
    }
</script>
