<template>
    <a-result :status="statusTitle" :title="statusTitle" :sub-title="description">
        <template #extra>
            <a-button type="primary">Back Home</a-button>
        </template>
    </a-result>
</template>

<script>
export default {
    props: {
        status: Number,
    },
    computed: {
        statusTitle() {
            return {
                404: '404',
                500: '500',
                404: '404',
                403: '403'
            }[this.status]
        },
        title() {
            return {
                503: '503: Service Unavailable',
                500: '500: Server Error',
                404: '404: Page Not Found',
                403: '403: Forbidden',
            }[this.status]
        },
        description() {
            return {
                503: 'Sorry, we are doing some maintenance. Please check back soon.',
                500: 'Whoops, something went wrong on our servers.',
                404: 'Sorry, the page you are looking for could not be found.',
                403: 'Sorry, you are forbidden from accessing this page.',
            }[this.status]
        },
    },
}
</script>
