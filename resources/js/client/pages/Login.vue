<template>
    <AuthLayout
        :title="currentTitle"
        :errorMessage="errorMessage"
        :actionText="actionText"
    >
        <form v-if="step === 'email'" @submit.prevent="handleCheckEmail" class="login-form" novalidate>
            <div class="a-input-text-group">
                <label for="email" class="a-form-label">Nhập số điện thoại di động hoặc email</label>
                <input id="email" type="email" v-model="form.email" required class="a-input-text" />
            </div>

            <button type="submit" class="a-button-primary" :disabled="isLoading">
                {{ isLoading ? 'Đang kiểm tra...' : 'Tiếp tục' }}
            </button>
        </form>

        <form v-else-if="step === 'password'" @submit.prevent="handleLogin" class="login-form" novalidate>
            <div class="email-display-box">
                <span class="email-text">{{ form.email }}</span>
                <a href="#" @click.prevent="step = 'email'" class="a-link-normal change-link">Thay đổi</a>
            </div>

            <div class="a-input-text-group">
                <div class="password-label-group">
                    <label for="password" class="a-form-label">Mật khẩu</label>
                    <a href="#" class="a-link-normal forgot-password">Quên mật khẩu?</a>
                </div>
                <input id="password" type="password" v-model="form.password" required class="a-input-text" autofocus />
            </div>

            <button type="submit" class="a-button-primary" :disabled="isLoading">
                {{ isLoading ? 'Đang đăng nhập...' : 'Đăng nhập' }}
            </button>
        </form>

        <div v-else-if="step === 'new_user'" class="login-form">
            <div class="email-display-box">
                <span class="email-text">{{ form.email }}</span>
                <a href="#" @click.prevent="step = 'email'" class="a-link-normal change-link">Thay đổi</a>
            </div>

            <p class="new-user-text">Hãy tạo tài khoản bằng email của bạn</p>

            <button @click="goToRegister" class="a-button-primary">
                Tiếp tục tạo tài khoản
            </button>
        </div>

        <template #footer-action>
            <div v-if="step === 'email'">
                <div class="a-divider a-divider-break">
                    <h5>Mới biết đến Amahuy?</h5>
                </div>
                <router-link to="/client/register" custom v-slot="{ navigate }">
                    <button @click="navigate" class="a-button-secondary">Tạo tài khoản Amahuy của bạn</button>
                </router-link>
            </div>

            <div v-if="step === 'new_user'">
                <div class="a-divider a-divider-break"></div>
                <div class="already-have-account" style="margin-top: 14px;">
                    <span style="font-weight: bold; display: block; margin-bottom: 4px;">Đã là khách hàng?</span>
                    <a href="#" @click.prevent="step = 'email'" class="a-link-normal">Đăng nhập bằng email hoặc số điện thoại di động khác</a>
                </div>
            </div>
        </template>
    </AuthLayout>
</template>

<script setup>
import { ref, reactive, computed } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '../stores/auth';
import api from '../services/api';
import AuthLayout from '@client/layouts/AuthLayout.vue';

const router = useRouter();
const authStore = useAuthStore();

const step = ref('email');

const form = reactive({
    email: '',
    password: '',
});

const isLoading = ref(false);
const errorMessage = ref('');


const currentTitle = computed(() => {
    if (step.value === 'email') return 'Đăng nhập hoặc tạo tài khoản';
    if (step.value === 'password') return 'Đăng nhập';
    return 'Có vẻ như bạn mới biết đến Amahuy';
});

const actionText = computed(() => {
    return step.value === 'password' ? 'đăng nhập' : 'tiếp tục';
});

// Gọi API kiểm tra Email
const handleCheckEmail = async () => {
    if (!form.email) return;

    isLoading.value = true;
    errorMessage.value = '';

    try {
        const response = await api.post('/check-email', { email: form.email });

        if (response.data.data.exists) {
            step.value = 'password';
        } else {
            step.value = 'new_user';
        }
    } catch (error) {
        errorMessage.value = 'Không thể kiểm tra thông tin lúc này. Vui lòng thử lại.';
    } finally {
        isLoading.value = false;
    }
};

// Gọi API Đăng nhập
const handleLogin = async () => {
    isLoading.value = true;
    errorMessage.value = '';

    try {
        const response = await authStore.login({
            email: form.email,
            password: form.password,
        });

        if (response.success) {
            router.push({ name: 'Home' });
        }
    } catch (error) {
        if (error.response && error.response.data) {
            errorMessage.value = error.response.data.message || 'Đã có lỗi xảy ra, vui lòng thử lại!';
        } else {
            errorMessage.value = 'Không thể kết nối đến máy chủ.';
        }
    } finally {
        isLoading.value = false;
    }
};

// Đẩy dữ liệu email sang trang đăng ký để khách không phải gõ lại
const goToRegister = () => {
    router.push({ name: 'Register', query: { email: form.email } });
};
</script>

<style scoped>
/* Khối hiển thị email + nút Thay đổi */
.email-display-box {
    display: flex;
    align-items: center;
    margin-bottom: 14px;
    font-size: 13px;
}
.email-display-box .email-text {
    font-weight: bold;
    margin-right: 8px;
}
.email-display-box .change-link {
    font-size: 12px;
}

.new-user-text {
    font-size: 13px;
    margin-bottom: 18px;
}
</style>
