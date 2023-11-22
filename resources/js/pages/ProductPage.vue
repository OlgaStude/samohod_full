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
    <img :src="'/storage/printer_imgs/'+product.img" alt="">
    <button v-if="is_logged" @click="add_to_card($event, product.id)">Добавить в корзину</button>
    {{ add_to_cart_success }}
      <a :href="$router.resolve({name: 'ProductPage', params: { id: product.id }}).href">{{ product.name }}</a>
      {{ product.price }}
      {{ product.manufacturer }}
      {{ product.year }}
      {{ product.model }}
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
        product_id: window.location.href.substring(window.location.href.lastIndexOf('/') + 1),
        product: null,
        add_to_cart_success: ''
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
        this.$axios
            .get("http://127.0.0.1:8000/api-samohod/product/"+this.product_id,{
                headers: { Authorization: 'Bearer '+ localStorage.token }
            })
            .then((response) => {
                this.product = response.data.content[0]
            });
  },
  methods: {
    add_to_card(e, id){
            this.$axios
            .request({
                url: "http://127.0.0.1:8000/api-samohod/cart/"+id,
                method: "POST",
                headers: {
                    Authorization: "Bearer " + localStorage.token,
                }
            })
            .then((response) => {
                this.add_to_cart_success = response.data.content.message
            });
    },
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