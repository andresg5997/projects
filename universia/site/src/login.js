import Vue from '../node_modules/vue/dist/vue'
import axios from 'axios'
import { url } from '../config'

// Vue.directive("userType-select", {
//   bind: function(el, binding, vnode) {
//     $(function() {
//     $(el).material_select();
//     });
//     var arg = binding.arg;
//     if (!arg) arg = "change";
//     arg = "on" + arg;
//     el[arg] = function() {
//     vnode.context.$data.form.userType = el.value;
//     };
//   },
//   unbind: function(el) {
//     $(el).material_select("destroy");
//   }
// });

const app = new Vue({
	el: '#app',
	data: {
		form: {
			firstName: '',
			lastName: '',
			email: '',
			username: '',
			password: '',
			userType: '',
			avatar: ''
		}
	},
	created() {
		// this.loadSelect()
	},
	methods: {
		loadSelect() {
			$('select').formSelect()
		},
		showData() {
			document.getElementById('data').innerHTML = JSON.stringify(this.form);
		},
		submit() {
			axios.post(url + 'users/signup', this.form)
			.then((res) => {
				if(res.data.err){
					console.log(err)
					return
				}
				console.log('User created!')
				document.getElementById('result').style.color = 'green';
				document.getElementById('result').innerHTML = JSON.stringify(res.data)
			})
			.catch((err) => { console.log(err) });
		}
	}
})