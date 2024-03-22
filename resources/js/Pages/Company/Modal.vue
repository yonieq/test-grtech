<template>
    <div>
        <a-modal v-model:open="valueModel" title="Company">
            <a-form :model="form" v-bind="layout" name="nest-messages" @submit.prevent="submit">
                <a-form-item name="name" label="Name">
                    <template v-if="props.IsEdit">
                        <a-input v-model:value="form.name" />
                        <InputError class="mt-2" :message="form.errors.name" />
                    </template>
                    <template v-else>
                        <a-input v-model:value="form.name" />
                        <InputError class="mt-2" :message="form.errors.name" />
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
                <a-form-item name="website" label="Website">
                    <template v-if="props.IsEdit">
                        <a-input v-model:value="form.website" />
                        <InputError class="mt-2" :message="form.errors.website" />
                    </template>
                    <template v-else>
                        <a-input v-model:value="form.website" />
                        <InputError class="mt-2" :message="form.errors.website" />
                    </template>
                </a-form-item>
                <a-form-item name="logo" label="Logo">
                    <template v-if="props.IsEdit">
                        <input type="file" @change="handleFileChange" accept=".png, .jpg, .jpeg"
                            @input="form.logo = $event.target.files[0]" />
                        <InputError class="mt-2" :message="form.errors.logo" />
                        <div v-if="form.logo" class="mt-4">
                            <img :src="form.imageEdit" alt="Company Logo" style="max-width: 100px;" />
                        </div>
                    </template>
                    <template v-else>
                        <input type="file" @change="handleFileChange" accept=".png, .jpg, .jpeg"
                            @input="form.logo = $event.target.files[0]" />
                        <InputError class="mt-2" :message="form.errors.logo" />
                        <div v-if="form.logo" class="mt-4">
                            <img :src="form.preview" alt="Company Logo" style="max-width: 100px;" />
                        </div>
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
import { ref, defineModel, reactive, defineProps, watch } from 'vue';
import { useForm, router } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import { notification } from 'ant-design-vue';

const openNotificationWithIcon = (type: string, messages: string, status: string) => {
    notification[type]({
        message: status,
        description: messages,
    });
};

const layout = {
    labelCol: { span: 8 },
    wrapperCol: { span: 16 },
};

interface dataCompany {
    name: string
    email: string
    logo: string | null
    website: string
    unique_id: string
}

const valueModel = defineModel<boolean>();

const props = defineProps<{
    dataDetail?: dataCompany,
    IsEdit?: boolean
}>()

const handleFileChange = (event: Event) => {
    const target = event.target as HTMLInputElement;
    const file: File | null = (target.files && target.files[0]) || null;

    if (file) {
        const url = URL.createObjectURL(file);
        form.preview = url;

        if (props.IsEdit) {
            form.imageEdit = url;
        }

    } else {
        form.preview = null;
    }
};

watch(() => props.dataDetail, (val) => {
    if (val) {
        form.name = val.name;
        form.email = val.email;
        form.logo = val.logo;
        form.website = val.website;
        form.unique_id = val.unique_id;
        form.imageEdit = val.logo;
    } else {
        form.name = '';
        form.email = '';
        form.logo = null;
        form.website = '';
        form.unique_id = '';
    }
})

const form = useForm({
    name: '',
    website: '',
    email: '',
    logo: null,
    preview: null,
    imageEdit: props.dataDetail?.logo ?? null,
    unique_id: '',
});


const submit = () => {
    if (props.IsEdit) {
        // console.log(form);
        form.post(route('company.store.image', { id: form.unique_id },), {
            preserveScroll: true,
            onSuccess: () => {
                router.visit('/company');
                openNotificationWithIcon('success', 'Success Update Data', 'Success');
            },
            onError: (e) => {
                console.log(e);
                openNotificationWithIcon('error', 'Error Update Data', 'Error');
            },
        });
    } else {
        form.post(route('company.store'), {
            onSuccess: () => {
                router.visit('/company');
                openNotificationWithIcon('success', 'Success Create Data', 'Success');
            },
            onError: (e) => {
                console.log(e);
                openNotificationWithIcon('error', 'Error Create Data', 'Error');
            },
        });

    }
};


</script>
