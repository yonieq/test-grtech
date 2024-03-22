<template>
    <div>
        <a-modal v-model:open="valueModel" title="Company">
            <a-form :model="form" v-bind="layout" name="nest-messages" @submit.prevent="submit">
                <a-form-item name="first_name" label="First Name">
                    <template v-if="props.IsEdit">
                        <a-input v-model:value="form.first_name" />
                        <InputError class="mt-2" :message="form.errors.first_name" />
                    </template>
                    <template v-else>
                        <a-input v-model:value="form.first_name" />
                        <InputError class="mt-2" :message="form.errors.first_name" />
                    </template>
                </a-form-item>
                <a-form-item name="last_name" label="Last Name">
                    <template v-if="props.IsEdit">
                        <a-input v-model:value="form.last_name" />
                        <InputError class="mt-2" :message="form.errors.last_name" />
                    </template>
                    <template v-else>
                        <a-input v-model:value="form.last_name" />
                        <InputError class="mt-2" :message="form.errors.last_name" />
                    </template>
                </a-form-item>
                <a-form-item name="company_id" label="Company">
                    <template v-if="props.IsEdit">
                        <a-select ref="select" v-model:value="form.company_id" :options="companies"
                            @change="handleChange">
                        </a-select>
                        <InputError class="mt-2" :message="form.errors.company_id" />
                    </template>
                    <template v-else>
                        <a-select ref="select" v-model:value="form.company_id" :options="companies"
                            @change="handleChange">
                        </a-select>
                        <InputError class="mt-2" :message="form.errors.company_id" />
                    </template>
                </a-form-item>
                <a-form-item name="email" label="Email">
                    <template v-if="props.IsEdit">
                        <a-input v-model:value="form.email" />
                        <InputError class="mt-2" :message="form.errors.email" />
                    </template>
                    <template v-else>
                        <a-input v-model:value="form.email" />
                        <InputError class="mt-2" :message="form.errors.email" />
                    </template>
                </a-form-item>
                <a-form-item name="phone" label="Phone">
                    <template v-if="props.IsEdit">
                        <a-input v-model:value="form.phone" />
                        <InputError class="mt-2" :message="form.errors.phone" />
                    </template>
                    <template v-else>
                        <a-input v-model:value="form.phone" />
                        <InputError class="mt-2" :message="form.errors.phone" />
                    </template>
                </a-form-item>

                <a-form-item :wrapper-col="{ offset: 8, span: 16 }">
                    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Submit
                    </button>
                </a-form-item>
            </a-form>
            <template #footer>
            </template>
        </a-modal>
    </div>
</template>

<script lang="ts" setup>
import { ref, defineModel, reactive, defineProps, watch, onMounted } from 'vue';
import { useForm, router, usePage } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import { notification } from 'ant-design-vue';

const openNotificationWithIcon = (type: string, messages: string, status: string) => {
    notification[type]({
        message: status,
        description: messages,
    });
};

const page = usePage();

const companies = ref<any>(page.props.companies);

// console.log(companies.value);

const handleChange = (value: string) => {
    form.company_id = value;
};

// console.log(companies.value);

const layout = {
    labelCol: { span: 8 },
    wrapperCol: { span: 16 },
};

interface dataCompany {
    id: number
    name: string
    value: string
    label: string
}

interface dataEmployee {
    id: string
    first_name: string
    last_name: string
    company_id: string
    email: string
    phone: string
    unique_id: string
}

const valueModel = defineModel<boolean>();

const props = defineProps<{
    dataDetail?: dataEmployee,
    IsEdit?: boolean
}>()

watch(() => props.dataDetail, (val) => {
    if (val) {
        form.first_name = val.first_name;
        form.last_name = val.last_name;
        form.company_id = val.company_id;
        form.email = val.email;
        form.phone = val.phone;
        form.unique_id = val.unique_id;
    } else {
        form.first_name = '';
        form.last_name = '';
        form.company_id = '';
        form.email = '';
        form.phone = '';
        form.unique_id = '';
    }
})

const form = useForm({
    first_name: '',
    last_name: '',
    company_id: '',
    phone: '',
    email: '',
    unique_id: '',
});


const submit = () => {
    if (props.IsEdit) {
        // console.log(form);
        form.put(route('employee.update', { id: form.unique_id },), {
            preserveScroll: true,
            onSuccess: () => {
                router.visit('/employee');
                openNotificationWithIcon('success', 'Success Update Data', 'Success');
            },
            onError: (e) => {
                console.log(e);
                openNotificationWithIcon('error', 'Error Update Data', 'Error');
            },
        });
    } else {
        form.post(route('employee.store'), {
            onSuccess: () => {
                router.visit('/employee');
                openNotificationWithIcon('success', 'Success Create New Data', 'Success');
            },
            onError: (e) => {
                console.log(e);
                openNotificationWithIcon('error', 'Error Create Data', 'Error');
            }
        });

    }
};


</script>
