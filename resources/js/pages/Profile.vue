<template>
    <div class="profile-container">
        <!-- Навигация -->
        <Navigation :user="user" />
        
        <!-- Основной контент -->
        <div class="profile-content">
            <!-- Заголовок -->
            <div class="profile-header">
                <h1 class="profile-title">
                    {{ title }}
                </h1>
                <p class="profile-description">
                    {{ description }}
                </p>
            </div>

            <div class="profile-grid">
                <!-- Информация о пользователе -->
                <div class="profile-section">
                    <div class="section-card">
                        <div class="section-header">
                            <h2 class="section-title">Информация профиля</h2>
                        </div>
                        
                        <div class="user-info">
                            <div class="user-avatar">
                                <img 
                                    v-if="user.avatar" 
                                    :src="user.avatar" 
                                    :alt="user.name"
                                    class="avatar-image"
                                />
                                <div v-else class="avatar-placeholder">
                                    {{ user.name.charAt(0).toUpperCase() }}
                                </div>
                            </div>
                            
                            <div class="user-details">
                                <h3 class="user-name">{{ user.name }}</h3>
                                <p class="user-email">{{ user.email }}</p>
                                <p class="user-joined">Присоединился {{ formatDate(user.created_at) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Статистика -->
                <div class="profile-section">
                    <div class="section-card">
                        <div class="section-header">
                            <h2 class="section-title">Статистика</h2>
                        </div>
                        
                        <div class="stats-grid">
                            <div class="stat-item">
                                <div class="stat-value">{{ stats.total_snippets }}</div>
                                <div class="stat-label">Всего сниппетов</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-value">{{ stats.public_snippets }}</div>
                                <div class="stat-label">Публичных</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-value">{{ stats.private_snippets }}</div>
                                <div class="stat-label">Приватных</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-value">{{ stats.total_views }}</div>
                                <div class="stat-label">Просмотров</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Редактирование профиля -->
                <div class="profile-section">
                    <div class="section-card">
                        <div class="section-header">
                            <h2 class="section-title">Редактировать профиль</h2>
                        </div>
                        
                        <form @submit.prevent="updateProfile" class="profile-form">
                            <div class="form-group"><TextInput id="name" v-model="form.name" label="Имя" required /></div>
                            <div class="form-group"><TextInput id="email" v-model="form.email" label="Email" type="email" required /></div>
                            <div class="form-group"><TextInput id="current_password" v-model="form.current_password" label="Текущий пароль" type="password" /></div>
                            <div class="form-group"><TextInput id="new_password" v-model="form.new_password" label="Новый пароль" type="password" /></div>
                            <div class="form-group"><TextInput id="new_password_confirmation" v-model="form.new_password_confirmation" label="Подтверждение пароля" type="password" /></div>
                            <div class="form-actions"><ButtonPrimary type="submit" :disabled="isLoading">{{ isLoading ? 'Сохранение...' : 'Сохранить изменения' }}</ButtonPrimary></div>
                        </form>
                    </div>
                </div>

                <!-- Последние сниппеты -->
                <div class="profile-section">
                    <div class="section-card">
                        <div class="section-header">
                            <h2 class="section-title">Последние сниппеты</h2>
                            <Link href="/my-snippets" class="section-link">Посмотреть все</Link>
                        </div>
                        
                        <div class="snippets-list">
                            <div v-if="recentSnippets && recentSnippets.length > 0">
                                <div v-for="snippet in recentSnippets" :key="snippet.id" class="snippet-item">
                                    <div class="snippet-info">
                                        <h3 class="snippet-title"><Link :href="`/code/${snippet.hash}`" class="snippet-link">{{ snippet.content.substring(0, 30) }}...</Link></h3>
                                        <div class="snippet-meta"><span class="snippet-language">{{ LANGUAGE_OPTIONS[snippet.language as keyof typeof LANGUAGE_OPTIONS] || snippet.language }}</span><span class="snippet-date">{{ formatDate(snippet.created_at) }}</span></div>
                                    </div>
                                </div>
                            </div>
                            <div v-else class="empty-section"><p>У вас пока нет сниппетов</p><Link href="/"><ButtonPrimary>Создать первый</ButtonPrimary></Link></div>
                        </div>
                    </div>
                </div>

                <!-- Удаление аккаунта -->
                <div class="profile-section">
                    <div class="section-card danger-zone">
                        <div class="section-header">
                            <h2 class="section-title">Опасная зона</h2>
                        </div>
                        
                        <div class="danger-content">
                            <p class="danger-description">
                                Удаление аккаунта необратимо. Все ваши сниппеты будут удалены.
                            </p>
                            
                            <ButtonSecondary @click="showDeleteModal = true">Удалить аккаунт</ButtonSecondary>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Модальное окно удаления -->
        <div v-if="showDeleteModal" class="modal-overlay" @click="showDeleteModal = false">
            <div class="modal-content" @click.stop>
                <div class="modal-header">
                    <h3 class="modal-title">Удалить аккаунт</h3>
                    <button @click="showDeleteModal = false" class="modal-close"><svg class="modal-close-icon" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" /></svg></button>
                </div>
                
                <div class="modal-body">
                    <p>Это действие необратимо. Все ваши сниппеты будут удалены.</p>
                    
                    <form @submit.prevent="deleteAccount" class="delete-form">
                        <div class="form-group"><TextInput id="delete_password" v-model="deleteForm.password" type="password" label="Введите пароль для подтверждения" required /></div>
                        <div class="modal-actions"><ButtonSecondary type="button" @click="showDeleteModal = false">Отмена</ButtonSecondary><ButtonPrimary type="submit" :disabled="isDeleting">{{ isDeleting ? 'Удаление...' : 'Удалить аккаунт' }}</ButtonPrimary></div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Футер -->
        <Footer />
    </div>
</template>

<script setup lang="ts">
import { ref, reactive } from 'vue';
import { router, Link } from '@inertiajs/vue3';
import { LANGUAGE_OPTIONS } from '@/types';
import Navigation from '@/components/Navigation.vue';
import Footer from '@/components/Footer.vue';
import ButtonPrimary from '@/components/buttons/ButtonPrimary.vue';
import ButtonSecondary from '@/components/buttons/ButtonSecondary.vue';
import TextInput from '@/components/inputs/TextInput.vue';
import { useToast } from '@/composables/useToast';

// Props от Inertia.js
interface Props {
    user: any;
    stats: any;
    recentSnippets: any[];
    title: string;
    description: string;
}
const props = defineProps<Props>();

const isLoading = ref(false);
const isDeleting = ref(false);
const showDeleteModal = ref(false);

const form = reactive({
    name: props.user.name,
    email: props.user.email,
    current_password: '',
    new_password: '',
    new_password_confirmation: ''
});

const deleteForm = reactive({
    password: ''
});

const errors = reactive({
    name: '',
    email: '',
    current_password: '',
    new_password: '',
    new_password_confirmation: '',
    password: ''
});
const { show: toast } = useToast();

const updateProfile = async () => {
    isLoading.value = true;
    
    try {
        await router.put('/profile', form, {
            onSuccess: () => {
                // Очищаем пароли после успешного обновления
                form.current_password = '';
                form.new_password = '';
                form.new_password_confirmation = '';
                toast('Профиль обновлён', 'success');
            },
            onError: (e) => { Object.assign(errors, e); toast('Проверьте данные', 'error'); },
            onFinish: () => { isLoading.value = false; },
        });
    } catch (error) {
        console.error('Ошибка:', error);
        toast('Ошибка соединения', 'error');
        isLoading.value = false;
    }
};

const deleteAccount = async () => {
    isDeleting.value = true;
    
    try {
        await router.delete('/profile', {
            data: deleteForm,
            onError: (e) => { Object.assign(errors, e); toast('Неверный пароль', 'error'); },
            onFinish: () => { isDeleting.value = false; },
        });
    } catch (error) {
        console.error('Ошибка:', error);
        toast('Ошибка соединения', 'error');
        isDeleting.value = false;
    }
};

const formatDate = (dateString: string) => new Date(dateString).toLocaleDateString('ru-RU', { year: 'numeric', month: 'long', day: 'numeric' });
</script>

<style scoped>
.profile-container {
    min-height: 100vh;
    background: var(--gradient-background);
    display: flex;
    flex-direction: column;
}

.profile-content {
    max-width: 80rem;
    margin: 0 auto;
    padding: 0 1rem;
    padding-top: 3rem;
    padding-bottom: 3rem;
    flex: 1;
}

@media (min-width: 640px) {
    .profile-content {
        padding: 0 1.5rem;
    }
}

@media (min-width: 1024px) {
    .profile-content {
        padding: 0 2rem;
    }
}

.profile-header {
    text-align: center;
    margin-bottom: 3rem;
}

.profile-title {
    font-size: 3rem;
    font-weight: 700;
    margin-bottom: 1.5rem;
    background: var(--gradient-primary);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

@media (min-width: 768px) {
    .profile-title {
        font-size: 3.75rem;
    }
}

.profile-description {
    font-size: 1.25rem;
    color: var(--color-textSecondary);
    max-width: 48rem;
    margin: 0 auto;
    line-height: 1.6;
}

.profile-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 2rem;
}

@media (min-width: 1024px) {
    .profile-grid {
        grid-template-columns: 1fr 1fr;
    }
}

.profile-section {
    display: flex;
    flex-direction: column;
}

.section-card {
    background-color: var(--color-surface);
    backdrop-filter: blur(12px);
    border-radius: 1rem;
    padding: 1.5rem;
    border: 1px solid var(--color-border);
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}

.section-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--color-text);
}

.section-link {
    color: var(--color-primary);
    text-decoration: none;
    font-size: 0.875rem;
}

.section-link:hover {
    text-decoration: underline;
}

.user-info {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.user-avatar {
    width: 4rem;
    height: 4rem;
    border-radius: 50%;
    overflow: hidden;
}

.avatar-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.avatar-placeholder {
    width: 100%;
    height: 100%;
    background: var(--gradient-primary);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    font-weight: 600;
}

.user-details {
    flex: 1;
}

.user-name {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--color-text);
    margin-bottom: 0.25rem;
}

.user-email {
    color: var(--color-textSecondary);
    margin-bottom: 0.25rem;
}

.user-joined {
    font-size: 0.875rem;
    color: var(--color-textSecondary);
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
}

.stat-item {
    text-align: center;
    padding: 1rem;
    background-color: var(--color-surface);
    border-radius: 0.5rem;
    border: 1px solid var(--color-border);
}

.stat-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--color-primary);
    margin-bottom: 0.25rem;
}

.stat-label {
    font-size: 0.875rem;
    color: var(--color-textSecondary);
}

.profile-form {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.form-label {
    font-size: 0.875rem;
    font-weight: 500;
    color: var(--color-text);
}

.form-input {
    padding: 0.75rem 1rem;
    border: 1px solid var(--color-border);
    border-radius: 0.75rem;
    background-color: var(--color-surface);
    color: var(--color-text);
    font-size: 0.875rem;
}

.form-input:focus {
    outline: none;
    border-color: var(--color-primary);
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.error-message {
    font-size: 0.875rem;
    color: var(--color-error);
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    margin-top: 1rem;
}

.snippets-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.snippet-item {
    padding: 1rem;
    background-color: var(--color-surface);
    border-radius: 0.5rem;
    border: 1px solid var(--color-border);
}

.snippet-title {
    font-size: 1rem;
    font-weight: 500;
    color: var(--color-text);
    margin-bottom: 0.5rem;
}

.snippet-link {
    color: var(--color-primary);
    text-decoration: none;
}

.snippet-link:hover {
    text-decoration: underline;
}

.snippet-meta {
    display: flex;
    gap: 1rem;
    font-size: 0.875rem;
    color: var(--color-textSecondary);
}

.empty-section {
    text-align: center;
    padding: 2rem 1rem;
    color: var(--color-textSecondary);
}

.danger-zone {
    border-color: var(--color-error);
}

.danger-content {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.danger-description {
    color: var(--color-textSecondary);
    font-size: 0.875rem;
}

.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
}

.modal-content {
    background-color: var(--color-surface);
    border-radius: 1rem;
    padding: 1.5rem;
    max-width: 32rem;
    width: 90%;
    border: 1px solid var(--color-border);
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.modal-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--color-text);
}

.modal-close {
    background: none;
    border: none;
    color: var(--color-textSecondary);
    cursor: pointer;
    padding: 0.25rem;
}

.modal-close-icon {
    width: 1.25rem;
    height: 1.25rem;
}

.modal-body {
    margin-bottom: 1rem;
}

.modal-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
}

.delete-form {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}
</style> 