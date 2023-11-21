<template>
    <header>
        <a href="/">О нас</a>
        <a href="/catalog">Каталог</a>
        <a href="/where">Где нас найти?</a>
        <div>
            <a href="/login">Авторизация</a>
            <a href="/registration">Регистрация</a>
        </div>

    </header>
    <main>
        <form action="">
            <input type="text" v-model="email" id="email" placeholder="Почта">
            <p>{{ errors.email }}</p>
            <input type="password" v-model="password" id="password" placeholder="Пароль">
            <p>{{ errors.password }}</p>
            <p>{{ errors.auth }}</p>
            <button type="submit" @click="register">Регистрация</button>
        </form>
    </main>
</template>

<style></style>


<script>
export default {
    name: "Login",
    data() {
        return {
            email: '',
            password: '',
            errors: {
                email: '',
                password: '',
                auth: '',
            }
        };
    },
    created() {},
    methods: {
        register(e) {
            e.preventDefault();

            this.errors = {
                email: '',
                password: '',
                auth: '',
            }
            this.$axios.get("/sanctum/csrf-cookie").then((response) => {
                this.$axios
                    .post("api-samohod/login", {
                        email: this.email,
                        password: this.password,
                    })
                    .then((response) => {
                        localStorage.token = response.data.token
                        window.location.href = "/";
                    })
                    .catch((err) => {
                        console.log(err.response.data.warning.message)
                        if(err.response.data.warning.message == 'Ненеудачный вход'){
                            this.errors.auth = 'Неверные почта или пароль';
                        }
                        err.response.data.warning.warnings.forEach(element => {
                            if (element.email) {
                                this.errors.email = element.email;
                                console.log(this.errors.email);
                            }
                            if (element.password) {
                                this.errors.password = element.password;
                                console.log(this.errors.password);
                            }
                        });
                        
                    });
            });
        }
    },
    beforeRouteEnter(to, from, next) {
        if (localStorage.token) {
            return next("/");
        }
        next();
    }
};
</script>
