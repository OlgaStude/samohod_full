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
    <div>
        <div>
            <input type="radio" @click="order_by" value="year" name="orderby" id="ob_1">
            <label for="ob_1">по году производства</label>
            <input type="radio" @click="order_by" value="name" name="orderby" id="ob_2">
            <label for="ob_2">по наименованию</label>
            <input type="radio" @click="order_by" value="price" name="orderby" id="ob_3">
            <label for="ob_3">по цене</label>
        </div>
        <div>
            <div v-for="i in index">
                <input type="radio" @click="filter" :value="categories[i-1].id" name="filter" :id="'filter_'+i">
                <label :for="'filter_'+i">{{ categories[i-1].name }}</label> 
            </div>
        </div>
    </div>
    <div>
        <div v-if="!filter_is_on" v-for="product in products">
          <img :src="'/storage/printer_imgs/'+product.img" alt="">
          {{ product.name }}
          {{ product.price }}
          <button v-if="is_logged" @click="add_to_card($event, product.id)">Добавить в корзину</button>
        </div>
        <div v-else v-for="product in products">
            <div v-if="filter_word == product.categories_id">
                <img :src="'/storage/printer_imgs/'+product.img" alt="">
                {{ product.name }}
                {{ product.price }}
                <button v-if="is_logged" @click="add_to_card($event, product.id)">Добавить в корзину</button>
            </div>
          </div>
    </div>
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
        products: [],
        categories: [],
        index: 0,
        filter_is_on: false,
        filter_word: ''
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
            .get("http://127.0.0.1:8000/api-samohod/products")
            .then((response) => {
                this.products = response.data.content;
            });
            this.$axios
            .get("http://127.0.0.1:8000/api-samohod/getcategories")
            .then((response) => {
                this.categories = response.data;
                this.index = this.categories.length
                console.log(this.index);
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
                console.log(response.data)
            });
    },
    order_by(e){
                console.log(e.target.value)
        this.$axios
            .request({
                url: "http://127.0.0.1:8000/api-samohod/productsreorder",
                method: "POST",
                data: {
                    orderby: e.target.value
                }
            })
            .then((response) => {
                this.products = response.data.content
            });
    },
    filter(e){
        console.log(e.target.value)
        this.filter_is_on = true;
        this.filter_word = e.target.value;
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