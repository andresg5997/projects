import Vue from 'vue'
import axios from 'axios'

Vue.directive("material-select", {
  bind: function(el, binding, vnode) {
    $(function() {
    $(el).material_select();
    });
    var arg = binding.arg;
    if (!arg) arg = "change";
    arg = "on" + arg;
    el[arg] = function() {
    vnode.context.$data.correo_select = el.value;
    };
  },
  unbind: function(el) {
    $(el).material_select("destroy");
  }
});

Vue.directive("material-select-1", {
  bind: function(el, binding, vnode) {
    $(function() {
    $(el).material_select();
    });
    var arg = binding.arg;
    if (!arg) arg = "change";
    arg = "on" + arg;
    el[arg] = function() {
    vnode.context.$data.variables_select = el.value;
    };
  },
  unbind: function(el) {
    $(el).material_select("destroy");
  }
});

const app = new Vue({
    el: '#app',
    data: {
        correo_select: '',
        variables_select: ''
    }
})
