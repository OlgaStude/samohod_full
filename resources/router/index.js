import { createWebHistory, createRouter } from "vue-router";

import Home from "../pages/Home.vue";
import Login from "../pages/Login.vue";
import Order from "../pages/Orders.vue";
import Registration from "../pages/Registration.vue";
import Cart from "../pages/Cart.vue";
import Admin from "../pages/Admin.vue";
import AddProduct from "../pages/AddProduct.vue";
import Catalog from "../pages/Catalog.vue";
import ProductPage from "../pages/ProductPage.vue";
import WhereToFind from "../pages/WhereToFind.vue";

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
    {
        name: "Admin",
        path: "/admin",
        component: Admin,
    },
    {
        name: "AddProduct",
        path: "/addproduct",
        component: AddProduct,
    },
    {
        name: "Catalog",
        path: "/catalog",
        component: Catalog,
    },
    {
        name: "ProductPage",
        path: "/product/:id",
        component: ProductPage,
    },
    {
        name: "WhereToFind",
        path: "/where",
        component: WhereToFind,
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes: routes,
});

export default router;
