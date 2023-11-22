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
    <div>
      <select  @change="change_category" name="" id="categories">
        <option value="">Выберите категорию</option>
        <option v-for="category in categories" :value="category.id">{{ category.name }}</option>
      </select>
      {{ category_delete_success }}
      <button @click="deleteCategory">Удалить категорию</button>
  </div>

  <form action="" method="post">
      <input type="text" v-model="new_category" name="name" placeholder="Название категории">
      {{ error.category }}
      {{ category_create_success }}
      <button @click="addCategory">Добавить категорию</button>
  </form>

  <a href="/addproduct">Добавить товар</a>

  <div>
    <div v-for="product in products">
      <img :src="'/storage/printer_imgs/'+product.img" alt="">
      <a :href="$router.resolve({name: 'ProductPage', params: { id: product.id }}).href">{{ product.name }}</a>
      {{ product.price }}
      <button @click="delete_product($event, product.id)">Удалить товар</button>
      <button @click="open_form($event, product.id)">Обновить товар</button>
    </div>
  </div>

  <form v-if="show_form" action="">
    <input type="text" v-model="updated_name" id="updated_name" placeholder="Имя">
      <p>{{ error.updated_name }}</p>
      <input type="file" ref="updated_img" id="img">
      <p>{{ error.updated_img }}</p>
      <input type="text" v-model="updated_price" id="updated_price" placeholder="цена">
      <p>{{ error.updated_price }}</p>
      <button @click="update_product">Отредактировать</button>
      <button @click="close_form">Закрыть</button>
  </form>


  <div>
    <div v-for="order in orders">
      <div>

        <p>{{ order.user_name }}</p>
        <p>{{ order.time }}</p>
        <div v-for="product in order.products">
          <a :href="$router.resolve({name: 'ProductPage', params: { id: product.id }}).href">{{ product.name }}</a>
          <p>{{ product.price }}</p>
        </div>
        <p>{{ order.status }}</p>
      </div>
      <div v-if="order.status == 'Новый'">
        <button @click="change_order_status($event, 'yes', order.id)">Потвердить заказ</button>
        <button @click="open_cancel_form">Отменить заказ</button>
        <div name="cancel_form" class="cancel_form">
          <textarea name="" id="" v-model="reason" cols="30" rows="10"></textarea>
          {{ error.reason }}
          <button @click="change_order_status($event, 'no', order.id)">Отменить заказ</button>
        </div>
      </div>
    </div>
  </div>

  </main>
</template>

<style>

.cancel_form{
  display: none;
}

</style>

<script>

import axios from 'axios'

export default {
  name: "Home",
  data() {
    return {
      categories: [],
      new_category: '',
      category_create_success: '',
      category_delete_success: '',
      category: '',
      updated_name: null,
      updated_price: null,
      updated_img: null,
      error: {
        category: null,
        updated_name: null,
        updated_price: null,
        updated_img: null,
        reason: null
      },
      products: [],
      show_form: false,
      product_id: 0,
      orders: [],
      reason: ''
    };
  },
  created() {
    this.$axios
            .get("http://127.0.0.1:8000/api-samohod/getcategories")
            .then((response) => {
                this.categories = response.data;
            });

            this.$axios
            .get("http://127.0.0.1:8000/api-samohod/products")
            .then((response) => {
                this.products = response.data.content;
            });
            this.$axios
            .get("http://127.0.0.1:8000/api-samohod/getallorders",{
                headers: { Authorization: 'Bearer '+ localStorage.token }
            })
            .then((response) => {
                console.log(response.data.content)
                this.orders = response.data.content
            });
            
  },
  methods: {
    open_cancel_form(e){
      e.preventDefault()
      var ele = document.getElementsByName("cancel_form");
      for(var i=0;i<ele.length;i++)
      ele[i].style.display = 'none'
    this.reason = ''
    e.target.nextElementSibling.style.display = 'block'
  },
    change_category(e){
      this.category = e.target.value
    },
    addCategory(e){
      e.preventDefault()
      this.error = {
        category: null
      }
      this.category_create_success = ''
      this.$axios
            .request({
                url: "http://127.0.0.1:8000/api-samohod/addcategory",
                method: "POST",
                headers: {
                    Authorization: "Bearer " + localStorage.token,
                },
                data: {
                  name: this.new_category
                }
            })
            .then((response) => {
                this.category_create_success = response.data.content.message
                this.categories = response.data.content.categories
                this.new_category = ''
            }).catch((err) => {
              console.log(err.response.data.warning.warnings)
              err.response.data.warning.warnings.forEach(element => {
                  if (element.name) {
                      this.error.category = element.name;
                      console.log(this.error.category);
                  }
              });
              
          });
    },
    deleteCategory(e){
      e.preventDefault()
      this.$axios
          .request({
              url: "http://127.0.0.1:8000/api-samohod/deletecategory",
              method: "POST",
              headers: {
                  Authorization: "Bearer " + localStorage.token,
              },
              data: {
                id: this.category
              }
          })
          .then((response) => {
              this.category_delete_success = response.data.content.message
              this.categories = response.data.content.categories
          })
    },
    delete_product(e, id){
      e.preventDefault()
      this.$axios
          .request({
              url: "http://127.0.0.1:8000/api-samohod/product/"+id,
              method: "DELETE",
              headers: {
                Authorization: "Bearer " + localStorage.token,
              }
          })
          .then((response) => {
              this.products = response.data.content.product
          })
    },
    open_form(e, id){
      e.preventDefault()
      this.show_form = true
      this.product_id = id;
    },
    close_form(e){
      e.preventDefault()
      this.show_form = false
      this.product_id = 0;
    },
    update_product(e){
      e.preventDefault()
      this.error = {
        updated_name: null,
        updated_price: null,
        updated_img: null
      }
      this.$axios
          .request({
              url: "http://127.0.0.1:8000/api-samohod/product/"+this.product_id,
              method: "POST",
              headers: {
                  Authorization: "Bearer " + localStorage.token,
                  'Content-Type': 'multipart/form-data'
              },
              data: {
                name: this.updated_name,
                img: this.$refs.updated_img.files[0],
                price: this.updated_price,
              }
          })
          .then((response) => {
              console.log(response.data)
              this.products = response.data.content.products
          }).catch((err) => {
              console.log(err.response.data.warning.warnings)
              err.response.data.warning.warnings.forEach(element => {
                  if (element.name) {
                      this.error.updated_name = element.name;
                  }
                  if (element.price) {
                      this.error.updated_price = element.price;
                  }
                  if (element.img) {
                      this.error.updated_img = element.img;
                  }
              });
              
          });
    },
    change_order_status(e, status, id){
      e.preventDefault()
            this.$axios
            .request({
                url: "http://127.0.0.1:8000/api-samohod/changeorderstatus",
                method: "POST",
                headers: {
                    Authorization: "Bearer " + localStorage.token,
                },
                data: {
                  id: id,
                  status: status,
                  reson: this.reason
                }
            })
            .then((response) => {
                this.$axios
              .get("http://127.0.0.1:8000/api-samohod/getallorders",{
                  headers: { Authorization: 'Bearer '+ localStorage.token }
              })
              .then((response) => {
                  console.log(response.data.content)
                  this.orders = response.data.content
                  this.reason = ''
                  e.target.nextElementSibling.style.display = 'none'
              });
            }).catch((err) => {
              this.error.reason = err.response.data.warning.warnings[0].reson
              
              
          });;
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