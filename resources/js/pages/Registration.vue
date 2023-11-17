<template>
    <main>
        <div
            class="row row-cols-1 row-cols-md-3 mb-3 text-center justify-content-center"
        >
            <div class="col">
                <div class="row">
                    <form>
                        <h1 class="h3 mb-3 fw-normal">
                            Пожалуйста заполните все поля
                        </h1>
                        <div class="form-floating mb-3">
                            <input
                                type="text"
                                class="form-control"
                                id="floatingFio"
                                placeholder="ФИО"
                            />
                            <label for="floatingFio">ФИО</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input
                                type="email"
                                class="form-control"
                                id="floatingInput"
                                placeholder="name@example.com"
                            />
                            <label for="floatingInput">Email</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input
                                type="password"
                                class="form-control"
                                id="floatingPassword"
                                placeholder="Password"
                            />
                            <label for="floatingPassword">Password</label>
                        </div>

                        <button
                            class="w-100 btn btn-lg btn-primary mb-3"
                            type="submit"
                            @click="register($event)"
                        >
                            Зарегистрироваться
                        </button>
                        <button
                            class="w-100 btn btn-lg btn-outline-info"
                            type="submit"
                        >
                            Назад
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </main>
</template>

<script>
export default {
    name: "Registration",
    data() {
        return {};
    },
    created() {
        this.$axios
            .get("http://127.0.0.1:8000/api-samohod/products")
            .then((response) => {
                console.log(response.data.content);
            });
    },
    methods: {
        register(e) {
            e.preventDefault();

            this.errors = {
                name: null,
                email: null,
                password: null,
            };
            this.$axios.get("/sanctum/csrf-cookie").then((response) => {
                this.$axios
                    .post("api-samohod/signup", {
                        name: this.name,
                        email: this.email,
                        password: this.password,
                    })
                    .then((response) => {
                        console.log(response);
                        // if (response.data.status == 200) {
                        //     window.location.href =
                        //         "/user/" + response.data.user_id;
                        // } else {
                        //     this.error = response.data.message;
                        // }
                    })
                    .catch((err) => {
                        console.log(err.response.data.warning.warnings[0].fio);
                        // if (err.response.data.errors.email) {
                        //     this.errors.email =
                        //         err.response.data.errors.email[0];
                        // }
                    });
            });
        },
    },
};
</script>
