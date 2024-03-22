<template>
    <div>

        <Head :title="title" />
        <AuthenticatedLayout>
            <template #header>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ page.props.title }} / {{
            page.props.breadcrumb }}</h2>
            </template>

            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <button
                        class="bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded mb-4"
                        @click="showModalCreate">New Data</button>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <a-table :dataSource="dataSource" :columns="columns">
                            <template #bodyCell="{ column, record }">
                                <template v-if="column.key === 'logo'">
                                    <img :src="record.logo" alt="Company Logo" style="max-width: 100px;" />
                                </template>
                                <template v-if="column.key === 'website'">
                                    <a :href="record.website" target="_blank">{{ record.website }}</a>
                                </template>
                                <template v-if="column.key === 'operation'">
                                    <a-button-group>
                                        <button @click="handleEdit(record)"
                                            class="bg-yellow-400 hover:bg-yellow-500 text-white font-bold py-2 px-4 mx-2 rounded-full">Edit</button>
                                        <button @click="handleDelete(record)"
                                            class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-full">Delete</button>
                                    </a-button-group>
                                </template>
                                <template v-else>
                                    {{ record[column.dataIndex] }}
                                </template>
                            </template>
                        </a-table>
                    </div>
                </div>
            </div>

            <CreateModal v-model="open" :dataDetail="dataSelected" :IsEdit="IsEdit" @ok="handleOk" />

        </AuthenticatedLayout>
    </div>
</template>

<script lang="ts" setup>
import { ref, watch, createVNode } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage, router } from '@inertiajs/vue3';
import CreateModal from './Modal.vue';
import { Modal } from 'ant-design-vue';
import { ExclamationCircleOutlined } from '@ant-design/icons-vue';
import { notification } from 'ant-design-vue';

const openNotificationWithIcon = (type: string, messages: string, status: string) => {
    notification[type]({
        message: status,
        description: messages,
    });
};

const page = usePage();

const title = page.props.title as string;

const columns = [
    {
        title: 'Name',
        dataIndex: 'name',
        sorter: true,
        width: '20%',
    },
    {
        title: 'Email',
        dataIndex: 'email',
    },
    {
        title: 'Logo',
        key: 'logo',
    },
    {
        title: 'Website',
        key: 'website',
    },
    {
        title: 'Action',
        key: 'operation',
        fixed: 'right',
        width: 100,
    },
];

const dataSource = ref(page.props.companies);

// Method untuk menampilkan modal create
const showModalCreate = () => {
    // Memanggil method showModal yang ada di komponen CreateModal
    // Melalui properti open
    open.value = true;
    IsEdit.value = false;
};

// Method untuk menangani aksi ketika tombol Edit diklik
const handleEdit = (record: any) => {
    // console.log('Edit data:', record);
    dataSelected.value = record;
    // console.log(dataSelected.value);
    open.value = true;
    IsEdit.value = true;
};

// Method untuk menangani aksi ketika tombol Delete diklik
const handleDelete = (record: any) => {
    router.delete(`/company/${record.unique_id}`, {
        onBefore: () => confirm('Are you sure you want to delete this data?'),
        onFinish: () => {
            router.visit('/company');
            openNotificationWithIcon('success', 'Success Delete Data', 'Success');
        },
        onError: (e) => {
            openNotificationWithIcon('error', 'Error Delete Data', 'Error');
        }
    })
};

// Modal state
const open = ref<boolean>(false);
const dataSelected = ref<any>(null);
const IsEdit = ref<boolean>(false);

// Method untuk menangani aksi ketika tombol OK di modal diklik
const handleOk = (e: MouseEvent) => {
    console.log(e);
    open.value = false;
};

watch(open, (val) => {
    if (!val) {
        dataSelected.value = null;
    }
})
</script>
