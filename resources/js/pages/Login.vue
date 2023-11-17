<template>
    <main>
        <div
            class="row row-cols-1 row-cols-md-3 mb-3 text-center justify-content-center"
        >
            <div class="col">
                <div class="row">
                    <form>
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
                        >
                            Войти
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
    name: "Login",
    data() {
        return {};
    },
    created() {},
    methods: {},
};
</script>
