<template>
    <header>
        <a href="/">О нас</a>
        <a href="/catalog">Каталог</a>
        <a href="/where">Где нас найти?</a>
        <div>
            <a v-if="is_admin" href="/admin">Админ панель</a>
            <a v-else href="/cart">Корзина</a>
            <button @click="logout">выход</button>
        </div>
    </header>
    <main>
        <div>
            <div v-for="order in orders">
                <div v-for="product in order.products">
                    <p>{{ product.name }}</p>
                    <p>{{ product.price }}</p>
                </div>
                <p>{{ order.status }}</p>
            </div>
        </div>
    </main>
</template>

<style></style>


<script>
export default {
    name: "Orders",
    data() {
        return {
            is_admin: false,
            orders: []
        };
    },
    created() {
        this.$axios
            .get("http://127.0.0.1:8000/api-samohod/getowninfo",{
                headers: { Authorization: 'Bearer '+ localStorage.token }
            })
            .then((response) => {
                if(response.data.role == 'admin'){
                    this.is_admin = true
                }
            });
            this.$axios
            .get("http://127.0.0.1:8000/api-samohod/order",{
                headers: { Authorization: 'Bearer '+ localStorage.token }
            })
            .then((response) => {
                console.log(response.data.content)
                this.orders = response.data.content
            });
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
    beforeRouteEnter(to, from, next) {
    if (!localStorage.token) {
      return next("/");
    }
    next();
}
};
</script>
