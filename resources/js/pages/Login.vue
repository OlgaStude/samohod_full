<template>
    <div class="container">
        <header>
            <a class="about" href="/">О нас</a>
            <a class="catalogue" href="/catalog">Каталог</a>
            <a class="where" href="/where">Где нас найти?</a>

            <a class="auth underline" href="/login">Авторизация</a>
            <a class="register" href="/registration">Регистрация</a>
        </header>
        <main>
            <h1>Авторизация</h1>
            <form action="">
                <input
                    type="text"
                    v-model="email"
                    id="email"
                    placeholder="Почта"
                />
                <p>{{ errors.email }}</p>
                <input
                    type="password"
                    v-model="password"
                    id="password"
                    placeholder="Пароль"
                />
                <p>{{ errors.password }}</p>
                <p>{{ errors.auth }}</p>
                <button class="auth_btn" type="submit" @click="register">
                    Войти
                </button>
            </form>
        </main>
    </div>
</template>

<style>
@font-face {
    font-family: font;
    src: url(storage/fonts/EkMuktaRegular-mOLv.ttf);
}

h1,
a,
p {
    font-family: "font";
}

* {
    margin: 0;
    padding: 0;
    background-color: #d2e4fc;
}

.container {
    width: 1920px;
}

header {
    background-color: #77a6f6;
    height: 227px;
}

header a {
    text-decoration: none;
    color: black;
    background-color: #77a6f6;
    font-size: 40px;
    position: relative;
    top: 8vh;
}

.about {
    margin-left: 5%;
}

.catalogue,
.where {
    margin-left: 8%;
}

.auth {
    margin-left: 16%;
    text-decoration: 2px underline white;
    text-underline-offset: 20px;
}

.register {
    margin-left: 8%;
}

h1 {
    font-weight: normal;
    margin-top: 5%;
    font-size: 64px;
}

main {
    text-align: center;
}

input {
    background-color: #ffffff;
    border-radius: 50px;
    border: none;
    width: 729px;
    height: 80px;
    margin-top: 3%;
    font-size: 32px;
    padding-left: 2%;
}

.auth_btn {
    width: 216px;
    height: 80px;
    background-color: white;
    border-radius: 10px;
    border: none;
    font-size: 32px;
    margin-top: 5%;
}
</style>

<script>
export default {
    name: "Login",
    data() {
        return {
            email: "",
            password: "",
            errors: {
                email: "",
                password: "",
                auth: "",
            },
        };
    },
    created() {},
    methods: {
        register(e) {
            e.preventDefault();

            this.errors = {
                email: "",
                password: "",
                auth: "",
            };
            this.$axios.get("/sanctum/csrf-cookie").then((response) => {
                this.$axios
                    .post("api-samohod/login", {
                        email: this.email,
                        password: this.password,
                    })
                    .then((response) => {
                        localStorage.token = response.data.token;
                        window.location.href = "/";
                    })
                    .catch((err) => {
                        console.log(err.response.data.warning.message);
                        if (
                            err.response.data.warning.message ==
                            "Ненеудачный вход"
                        ) {
                            this.errors.auth = "Неверные почта или пароль";
                        }
                        err.response.data.warning.warnings.forEach(
                            (element) => {
                                if (element.email) {
                                    this.errors.email = element.email;
                                    console.log(this.errors.email);
                                }
                                if (element.password) {
                                    this.errors.password = element.password;
                                    console.log(this.errors.password);
                                }
                            }
                        );
                    });
            });
        },
    },
    beforeRouteEnter(to, from, next) {
        if (localStorage.token) {
            return next("/");
        }
        next();
    },
};
</script>
