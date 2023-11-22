<template>
    <router-view></router-view>
    <div class="container_footer">
        <footer>
            <small class="copyright">&copy;copyright 2023 Copy Star</small>
            <br />
            <small class="right_reserved">Все права защищены</small>
            <img src="storage/img/soc_sites.png">
        </footer>
    </div>
</template>

<style>
body {
    overflow-x: auto;
}

footer {
    width: 1920px;
    background-color: #77a6f6;
    margin-top: 5%;
    height: 227px;
}

small {
    font-size: 20px;
    background-color: #77a6f6;
    margin-left: 5%;
}

.copyright {
    position: relative;
    top: 8vh;
}



img {
    background-color: #77a6f6;
    position: relative;
    margin-left: 50%;
    margin-top: 2%
}

</style>

<script>
export default {
    name: "App",
    data() {
        return {
            isLogged: false,
        };
    },
    created() {},
    methods: {},
};
</script>
