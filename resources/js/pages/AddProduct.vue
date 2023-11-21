<template>
  <header>
    <a href="/">О нас</a>
    <a href="/catalog">Каталог</a>
    <a href="/where">Где нас найти?</a>
    <div>
        <a href="/admin">Админ панель</a>
        <button @click="logout">выход</button>
    </div>

</header>
  <main>
    <form action="">
      <input type="text" v-model="name" id="name" placeholder="Имя">
      <p>{{ errors.name }}</p>
      <input type="file" ref="img" id="img">
      <p>{{ errors.img }}</p>
      <select @change="change_category" name="" id="categories">
        <option value="">Выберите категорию</option>
        <option v-for="category in categories" :value="category.id">{{ category.name }}</option>
      </select>
      <p>{{ errors.category_id }}</p>
      <input type="text" v-model="manufacturer" id="manufacturer" placeholder="Производитель">
      <p>{{ errors.manufacturer }}</p>
      <input type="text" v-model="year" id="year" placeholder="Год выпуска">
      <p>{{ errors.year }}</p>
      <input type="model" v-model="model" id="model" placeholder="Модель">
      <p>{{ errors.model }}</p>
      <input type="price" v-model="price" id="price" placeholder="цена">
      <p>{{ errors.price }}</p>
      <p>{{ product_add_success }}</p>
      <button id="send_btn" @click="add_product">Регистрация</button>
  </form>
  </main>
</template>

<style></style>

<script>

import axios from 'axios'

export default {
  name: "Home",
  data() {
    return {
      categories: [],
      category: '',
      name: null,
      manufacturer: null,
      year: null,
      model: null,
      price: null,
      product_add_success: '',
      errors: {
        name: null,
        img: null,
        categories: null,
        manufacturer: null,
        year: null,
        model: null,
        price: null,
      }
    };
  },
  created() {
    this.$axios
            .get("http://127.0.0.1:8000/api-samohod/getcategories",{
                headers: { Authorization: 'Bearer '+ localStorage.token }
            })
            .then((response) => {
                this.categories = response.data;
            });
  },
  methods: {
    change_category(e){
      console.log(e.target.value)
      this.category = e.target.value
    },
    add_product(e){
      e.preventDefault();
      this.errors = {
        name: null,
        img: null,
        categories_id: null,
        manufacturer: null,
        year: null,
        model: null,
        price: null,
      }
      this.$axios
            .request({
                url: "http://127.0.0.1:8000/api-samohod/product",
                method: "POST",
                headers: {
                    Authorization: "Bearer " + localStorage.token,
                    'Content-Type': 'multipart/form-data'
                },
                data: {
                  name: this.name,
                  img: this.$refs.img.files[0],
                  category_id: this.category,
                  manufacturer: this.manufacturer,
                  year: this.year,
                  model: this.model,
                  price: this.price,
                }
            })
            .then((response) => {
                console.log(response.data.content.message)
                this.product_add_success = response.data.content.message
                this.category = ''
                this.name = null
                this.manufacturer = null
                this.year = null
                this.model = null
                this.price = null
                this.$refs.img.files[0] = null
            }).catch((err) => {
              console.log(err.response.data.warning.warnings)
              err.response.data.warning.warnings.forEach(element => {
                  if (element.name) {
                      this.errors.name = element.name;
                  }
                  if (element.img) {
                      this.errors.img = element.img;
                  }
                  if (element.category_id) {
                      this.errors.category_id = element.category_id;
                  }
                  if (element.manufacturer) {
                      this.errors.manufacturer = element.manufacturer;
                  }
                  if (element.year) {
                      this.errors.year = element.year;
                  }
                  if (element.model) {
                      this.errors.model = element.model;
                  }
                  if (element.price) {
                      this.errors.price = element.price;
                  }
              });
              
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
    if (localStorage.token) {
      axios
            .get("http://127.0.0.1:8000/api-samohod/getowninfo",{
                headers: { Authorization: 'Bearer '+ localStorage.token }
            })
            .then((response) => {
                      if(response.data.role != 'admin'){
                          return next("/");

                      };
            });
    }
    next();
}
};
</script>