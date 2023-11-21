<template>
    <header>
        <a href="/">О нас</a>
        <a href="/catalog">Каталог</a>
        <a href="/where">Где нас найти?</a>
        <div v-if="is_logged">
            <a v-if="is_admin" href="/admin">Админ панель</a>
            <a v-else href="/cart">Корзина</a>
            <button @click="logout">выход</button>
        </div>
        <div v-else>
            <a href="/login">Авторизация</a>
            <a href="/registration">Регистрация</a>
        </div>

    </header>
    <main>
        
    </main>
</template>

<style></style>


<script>
export default {
    name: "Home",
    data() {
        return {
            is_logged: false,
            is_admin: false,
            
        };
    },
    created() {
        if(localStorage.token){
            this.is_logged = true
            this.$axios
            .get("http://127.0.0.1:8000/api-samohod/getowninfo",{
                headers: { Authorization: 'Bearer '+ localStorage.token }
            })
            .then((response) => {
                if(response.data.role == 'admin'){
                    this.is_admin = true
                }
            });
        }
    },
    methods: {
        logout() {
            console.log(localStorage.token);
            this.$axios
            .request({
                url: "http://127.0.0.1:8000/api-samohod/logout",
                method: "POST",
                headers: {
                    Authorization: "Bearer " + localStorage.token,
                }
            })
            .then((response) => {
                localStorage.removeItem('token');
                window.location.href = "/";
            });
        }
        
    },
};
</script>
