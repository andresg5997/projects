import axios from 'axios'
import Vue from 'vue'

export function getTareas(user_id){
    return axios.get(laroute.route('api.usuarios.tareas', {id: user_id}))
}

export function getMarcas(user_id){
    return axios.get(laroute.route('api.marcas', {id: user_id}))
}

export function getUsuarios(user_id){
    return axios.get(laroute.route('api.usuario.usuarios', {id: user_id}))
}
