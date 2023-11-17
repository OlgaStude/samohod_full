<template>
    <div class="container">
        <header>
            <div
                class="d-flex flex-column flex-md-row align-items-center pb-3 mb-4 border-bottom"
            >
                <a
                    href="/"
                    class="d-flex align-items-center text-dark text-decoration-none"
                >
                    <span class="fs-4">«Самоход»</span>
                </a>

                <nav class="d-inline-flex mt-2 mt-md-0 ms-md-auto">
                    <a
                        class="me-3 py-2 text-dark text-decoration-none"
                        href="/registration"
                        >Регистрация</a
                    >
                    <a
                        class="me-3 py-2 text-dark text-decoration-none"
                        href="/login"
                        >Авторизация</a
                    >
                    <a
                        class="me-3 py-2 text-dark text-decoration-none"
                        href="/orders"
                        >Мои заказы</a
                    >
                    <a
                        class="me-3 py-2 text-dark text-decoration-none"
                        href="/cart"
                        >Корзина</a
                    >
                </nav>
            </div>

            <div class="pricing-header p-3 pb-md-4 mx-auto text-center">
                <h1 class="display-4 fw-normal">Каталог товаров</h1>
            </div>
        </header>

        <router-view></router-view>
        <footer class="pt-4 my-md-5 pt-md-5 border-top">
            <div class="row">
                <div class="col-12 col-md">
                    <small class="d-block mb-3 text-muted"
                        >&copy; 2017–2021</small
                    >
                </div>
            </div>
        </footer>
    </div>
</template>

<script>
export default {
    name: "App",
    data() {
        return {
            isLogged: false,
        };
    },
    created() {
        if (globalIsLogged) {
            this.isLogged = true;
        }
    },
    methods: {
        logout(e) {
            e.preventDefault();
            this.$axios.get("/sanctum/csrf-cookie").then((response) => {
                this.$axios
                    .components("/api/logout")
                    .then((response) => {
                        if (response.data.status == 200) {
                            window.location.href = "/";
                        } else {
                            console.log(response);
                        }
                    })
                    .catch(function (error) {
                        console.error(error);
                    });
            });
        },
    },
};
</script>
