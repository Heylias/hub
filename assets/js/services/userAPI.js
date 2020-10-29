import Axios from "axios"
import { USERS_API } from "../config"

function findAll(){
    return Axios.get(USERS_API)
                .then(response => response.data['hydra:member'])
                .catch(error => console.log(error))
}

function find(id){
    return Axios.get(`${USERS_API}/${id}`)
                .then(response=>response.data)
}

function deleteUser(id) {
    return  Axios.delete(`${USERS_API}/${id}`)
}

function updateUser(id, user, password){
    return Axios.put(`${USERS_API}/${id}`, {...user, password: password})
}

function createUser(user){
    return Axios.post(USERS_API, user)
                .then(response => response.data['hydra:member'])
}


export default {
    findAll: findAll,
    find: find,
    delete: deleteUser,
    update : updateUser,
    create: createUser
}