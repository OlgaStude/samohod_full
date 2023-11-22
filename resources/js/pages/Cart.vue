<template>
    <header>
        <a class="about" href="/">О нас</a>
        <a class="catalogue" href="/catalog">Каталог</a>
        <a class="where" href="/where">Где нас найти?</a>
        <div class="admin_div">
            <a v-if="is_admin" href="/admin">Админ панель</a>
            <a v-else href="/cart" class="cart underline">Корзина</a>
            <a @click="logout" class="logout">Выйти</a>
        </div>
    </header>
    <main>
        <form v-if="order_form_is_on" action="">
            <p>Подтвердите пароль для формирования заказа</p>
            <input v-model="password" type="password" name="password" id="password" placeholder="Пароль">
            <p v-if="password_message != ''">{{ password_message }}</p>
            <p v-else>{{ order_message }}</p>
            <button @click="order">Сформировать заказ</button>
            <button @click="close_order_form">Закрыть форму</button>
        </form>
        <a href="/orders">Список заказов</a>
        <button @click="open_order_form">Оформить заказ</button>
        <div v-for="product in products">
            <img :src="'/storage/printer_imgs/'+product.img" alt="">
            {{ product.name }}
            {{ product.price }}
            <button @click="delete_from_cart($event, product.id)">Удалить из корзины</button>
        </div>
    </main>
</template>

<style></style>

<script>
export default {
    name: "Home",
    data() {
        return {
            is_admin: false,
            products: [],
            order_message: '',
            password_message: '',
            order_form_is_on: false,
            password: '',
            user_email: ''
        };
    },
    created() {
        this.$axios
            .get("http://127.0.0.1:8000/api-samohod/getowninfo",{
                headers: { Authorization: 'Bearer '+ localStorage.token }
            })
            .then((response) => {
                this.user_email = response.data.email;
                if(response.data.role == 'admin'){
                    this.is_admin = true
                }
            });
            this.$axios
            .get("http://127.0.0.1:8000/api-samohod/cart",{
                headers: { Authorization: 'Bearer '+ localStorage.token }
            })
            .then((response) => {
                this.products = response.data.content
            });
    },
    methods: {
        open_order_form(e){
            e.preventDefault();
            this.order_form_is_on = true;
        },
        close_order_form(e){
            e.preventDefault();
            this.order_form_is_on = false;
        },
        order(e){
            this.order_message = ''
            this.password_message = ''
            e.preventDefault();
            this.$axios
            .request({
                url: "http://127.0.0.1:8000/api-samohod/checkpassword",
                method: "POST",
                headers: {
                    Authorization: "Bearer " + localStorage.token,
                },
                data: {
                    password: this.password,
                    email: this.user_email
                }
            })
            .then((response) => {
                this.$axios
            .request({
                url: "http://127.0.0.1:8000/api-samohod/order/",
                method: "POST",
                headers: {
                    Authorization: "Bearer " + localStorage.token,
                }
            })
            .then((response) => {
                this.products = []
                this.order_message = response.data.content.message
                this.password = ''
            }).catch((err) => {
                this.order_message = err.response.data.content.message
              
          });
            }).catch((err) => {
                this.password_message = err.response.data.warning.message
              
            });
            
        },
        delete_from_cart(e, id){
            e.preventDefault();
            this.$axios
            .request({
                url: "http://127.0.0.1:8000/api-samohod/cart/"+id,
                method: "DELETE",
                headers: {
                    Authorization: "Bearer " + localStorage.token,
                }
            })
            .then((response) => {
                this.products = response.data.content.content
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
    
    beforeRouteEnter(to, from, next) {
    if (!localStorage.token) {
      return next("/");
    }
    next();
}
};
</script>
