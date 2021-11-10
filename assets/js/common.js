$(function () {

  window.VMContact = new Vue({
    el: '#contact_form',
    delimiters: ['${', '}'],
    data() {
      return {
        countries: [],
        cities: [],
        models: [],
        chosen_product: {
          name: '',
          code: ''
        },
        search_city_val: '',
        style_rules: '',
        success_text: '',
        form: {
          surname: {
            value: "",
            error: "",
          },
          name: {
            value: "",
            error: "",
          },
          model: {
            value: "",
          },
          country: {
            value: "",
            error: "",
          },
          city: {
            value: "",
            error: "",
          },
          phone: {
            value: "",
            error: "",
          },
          email: {
            value: "",
            error: "",
          },
          message: {
            value: "",
          },
          accept: {
            value: "",
            error: "",
          },
        },
        form_default: {},
        form_overlay: false,
      }
    },
    watch: {},
    computed: {},
    created() {
      this.getCities();
      this.getModels();
    },
    mounted() {
      $('.vs__search').removeAttr('type');
    },
    methods: {
      onFocus() {
        $(event.target).attr('placeholder', '');
      },
      onBlur(text) {
        $(event.target).attr('placeholder', text);
      },
      async getCities() {
        const response = await Promise.all([
          axios.get("/wp-json/api/countries/get/").catch((error) => {
            console.log(error);
          }),
          axios.get("/wp-json/api/cities/get/").catch((error) => {
            console.log(error);
          }),
        ]);

        this.$set(this, "countries", response[0].data.countries);
        this.$set(this, "cities", response[1].data.cities);
      },
      getModels() {
        if ($('#models_json').length) {
          this.$set(this, 'models', JSON.parse($('#models_json').html()));
          this.chosen_product = null;
        }
      },
      showCities: function () {
        if (this.$refs.usernameInput !== undefined) {
          if (
            this.$refs.usernameInput.searching === true &&
            this.search_city_val.length > 1
          ) {
            return ".city-chooser .vs__dropdown-option {display: block}";
          }
        }
        return ".city-chooser .vs__dropdown-option {display: none}";
      },
      filterСityBy: function (option, label, search) {
        this.search_city_val = search;
        if (search.length < 2) {
          this.style_rules =
            ".city-chooser .vs__dropdown-option {display: block}";
          return;
        }
        this.style_rules = ".city-chooser .vs__dropdown-option {display: block}";
        return (label || "").toLowerCase().indexOf(search.toLowerCase()) > -1;
      },
      formSend: async function () {
        const self = this;
        const stop = this.validate(this.form);

        if (stop === true) {
          return;
        }

        $('#contact_form').css('height', $('#contact_form').outerHeight());

        this.form_overlay = true;

        if (!this.chosen_product.name.length) {
          this.chosen_product.name = getUrlParameter('model') + ' ' + (getUrlParameter('type') ? getUrlParameter('type') : '');
        }

        const data = {
          chosen_product: this.chosen_product,
          form_data: this.form,
        };

        const form_data = new FormData();

        form_data.append("data", JSON.stringify(data));

        const response = await axios
          .post("/wp-json/api/order/post/", form_data)
          .catch(function (error) {
            console.log(error);
          });

        console.log(response);

        if (response.data.status === "success") {
          this.success_text = response.data.message;
          this.form = this.cloneObj(this.form_default);
          this.chosen_equipment = {
            items: [],
            sum: 0,
          };
          this.chosen_services = {
            items: [],
            upgrade: 0,
            sum: 0,
          };
          fullpage_api.reBuild();
        }
        setTimeout(() => {
          self.form_overlay = false;
        }, 500);
      },
      validate(form) {
        let stop = false;
        for (let key in form) {
          if (typeof form[key].error === "undefined") {
            continue;
          }
          let error = "";
          switch (key) {
            case "name":
              if (form[key].value.length < 2) {
                error = "Обязательное поле";
              }
              break;
            case "phone":
              if (form[key].value.length < 9) {
                error = "Обязательное поле";
              }
              break;
            case "email":
              if (form[key].value.length < 4) {
                error = "Обязательное поле";
              } else if (
                !form[key].value.includes("@") ||
                !form[key].value.includes(".")
              ) {
                error = "Адрес должен содержать знак @ и .";
              }
              break;
            case "accept":
              if (form[key].value !== true) {
                error = "Согласие обязательно";
              }
              break;
            default:
              if (form[key].value.length < 1) {
                error = "Обязательное поле";
              }
              break;
          }
          if (error !== "") {
            stop = true;
          }
          form[key].error = error;
        }
        return stop;
      },
      cloneObj: function (object) {
        return JSON.parse(JSON.stringify(object));
      }
    },
    components: {
      vSelect: VueSelect.VueSelect,
      vStyle: {
        render: function (createElement) {
          return createElement("style", this.$slots.default);
        },
      },
    }
  })

  getUrlParameter = function (name) {
    const results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
    if (results == null) {
      return null;
    } else {
      return results[1] || 0;
    }
  }
});