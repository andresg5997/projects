import Vue from '../node_modules/vue/dist/vue'
import axios from 'axios'
import { url } from '../config'

const app = new Vue({
	el: '#app',
	data: {
		data: 'Hello you all!',
		users: []
	},
	methods: {
		getData(){
			axios.get(url + 'users')
			.then((res) => {
				var data = document.getElementById("data");
				var newdata = document.getElementById("newdata");
				data.innerHTML = JSON.stringify(res.data);
				newdata.innerHTML = JSON.stringify(res.data);

				console.log(res.data);
			})
			.catch((err) => { console.log(err) });
		}
	}
})