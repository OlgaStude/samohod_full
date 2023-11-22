<template>
  <header>
    <a class="about" href="/">О нас</a>
    <a class="catalogue" href="/catalog">Каталог</a>
    <a class="where underline" href="/where">Где нас найти?</a>
    <div v-if="is_logged" class="admin_div">
        <a v-if="is_admin" href="/admin">Админ панель</a>
        <a v-else href="/cart" class="cart">Корзина</a>
        <a @click="logout" class="logout">Выйти</a>
    </div>
    <div v-else class="guest_nav">
        <a href="/login" class="auth">Авторизация</a>
        <a href="/registration" class="register_nav">Регистрация</a>
    </div>

</header>
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