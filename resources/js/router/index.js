import { createWebHistory, createRouter } from "vue-router";

import Home from "../pages/Home.vue";
import Login from "../pages/Login.vue";
import Order from "../pages/Orders.vue";
import Registration from "../pages/Registration.vue";
import Cart from "../pages/Cart.vue";

export const routes = [
    {
        name: "Home",
        path: "/",
        component: Home,
    },
    {
        name: "Login",
        path: "/login",
        component: Login,
    },
    {
        name: "Order",
        path: "/orders",
        component: Order,
    },
    {
        name: "Registration",
        path: "/registration",
        component: Registration,
    },
    {
        name: "Cart",
        path: "/cart",
        component: Cart,
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes: routes,
});

export default router;
