<template>
  <div>
    <div class="card">
      <div class="card-header">
        <div class="form-row justify-content-between">
          <div class="col-md-2">
            <input
              type="text"
              name="title"
              v-model="title"
              placeholder="Product Title"
              class="form-control"
            />
          </div>
          <div class="col-md-2">
            <Select2
              :options="formattedVariants"
              :settings="{ width: '100%' }"
              v-model="variant"
            />
          </div>

          <div class="col-md-3">
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text">Price Range</span>
              </div>
              <input
                type="text"
                name="price_from"
                v-model="priceFrom"
                aria-label="First name"
                placeholder="From"
                class="form-control"
              />
              <input
                type="text"
                name="price_to"
                v-model="priceTo"
                aria-label="Last name"
                placeholder="To"
                class="form-control"
              />
            </div>
          </div>
          <div class="col-md-2">
            <input
              type="date"
              name="date"
              placeholder="Date"
              v-model="date"
              class="form-control"
            />
          </div>
          <div class="col-md-1">
            <button
              type="submit"
              @click="getProducts(1)"
              class="btn btn-primary float-right"
            >
              <i class="fa fa-search"></i>
            </button>
          </div>
        </div>
      </div>

      <div class="card-body">
        <div class="table-response">
          <table class="table">
            <thead>
              <tr>
                <th>#</th>
                <th>Title</th>
                <th style="width: 45%">Description</th>
                <th style="width: 35%">Variant</th>
                <th width="150px">Action</th>
              </tr>
            </thead>

            <tbody>
              <tr v-for="(item, index) in this.products.data" :key="index">
                <td>{{ initialNumber + index }}</td>
                <td>
                  {{ item.title }} <br />
                  Created at : {{ diff(item.created_at) }} hours ago
                </td>
                <td>{{ item.description }}</td>
                <td>
                  <dl
                    class="row mb-0"
                    style="height: 80px; overflow: hidden"
                    v-for="(variant, variantIndex) in item.variant_prices"
                    :key="index + 'variant' + variantIndex"
                  >
                    <dt class="col-sm-3 pb-0">
                      {{ getVariantCommonName(variant) }}
                    </dt>
                    <dd class="col-sm-9">
                      <dl class="row mb-0">
                        <dt class="col-sm-4 pb-0">
                          Price : {{ variant.price }}
                        </dt>
                        <dd class="col-sm-8 pb-0">
                          InStock : {{ variant.stock }}
                        </dd>
                      </dl>
                    </dd>
                  </dl>
                  <button class="btn btn-sm btn-link">Show more</button>
                </td>
                <td>
                  <div class="btn-group btn-group-sm">
                    <a href="" class="btn btn-success">Edit</a>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <div class="card-footer">
        <div class="row justify-content-between">
          <div class="col-md-6">
            <p>
              Showing {{ initialNumber }} to {{ endNumber }} out of
              {{ totalNumber }}
            </p>
          </div>
          <div class="col-md-2">
            <pagination
              :data="products"
              @pagination-change-page="getProducts"
            ></pagination>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { showSuccess, showError } from "./../helpers.js";
import moment from "moment";
import Select2 from "v-select2-component";

export default {
  data() {
    return {
      products: {},
      initialNumber: 0,
      endNumber: 0,
      totalNumber: 0,
      title: "",
      priceFrom: "",
      priceTo: "",
      variants: [],
      variant : '',
      date : '',
    };
  },
  components: {
    Select2,
  },
  computed: {
    formattedVariants() {
      let arr = [];
      for (let item of this.variants) {
        let option = {
          text: item.title,
          children: [],
          element: HTMLOptGroupElement,
        };
        let childArr = [];
        for (let item1 of item.variant_value) {
          childArr.push(item1.variant);
        }
        childArr = childArr.filter(this.onlyUnique)
        for (let item2 of childArr) {
          option.children.push({id : item2, text : item2})
        }
        arr.push(option);
      }
      return arr;
    },
  },
  methods: {
    onlyUnique(value, index, self) {
      return self.indexOf(value) === index;
    },
    diff(date) {
      let now = moment();
      return moment(now).diff(date, "hours");
    },
    getVariantCommonName(variant) {
      let title = "";
      if (variant.variant_one) {
        title += variant.variant_one.variant;
      }
      if (variant.variant_two) {
        title += "/" + variant.variant_two.variant;
      }
      if (variant.variant_three) {
        title += "/" + variant.variant_three.variant;
      }
      return title;
    },
    getVariants() {
      axios.get("/get-variants").then((res) => {
        this.variants = res.data;
      });
    },
    getProducts(page = 1) {
      axios
        .get(
          `/product?isAjax=1&page=${page}&title=${this.title}&priceFrom=${this.priceFrom}&priceTo=${this.priceTo}&variant=${this.variant}&date=${this.date}`
        )
        .then((res) => {
          this.products = Object.assign({}, res.data.products);
          this.initialNumber = res.data.products.from;
          this.endNumber = res.data.products.to;
          this.totalNumber = res.data.total;
        })
        .catch((err) => {
          showError(err.response.data.message);
        });
    },
  },
  mounted() {
    this.getProducts();
    this.getVariants();
  },
};
</script>

<style>
</style>