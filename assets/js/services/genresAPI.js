import Axios from "axios"
import { GENRES_API } from "../config"

function findAll(){
    return Axios.get(GENRES_API)
                .then(response => response.data['hydra:member'])
                .catch(error => console.log(error))
}

function find(id){
    return Axios.get(`${GENRES_API}/${id}`)
                .then(response=>response.data)
}

function deleteGenre(id) {
    return  Axios.delete(`${GENRES_API}/${id}`)
}

function updateGenre(id, genre){
    return Axios.put(`${GENRES_API}/${id}`, genre)
}

function createGenre(genre){
    return Axios.post(GENRES_API, genre)
}

function displayFanfictions(id){
    return Axios.get(`${GENRES_API}/${id}/fanfictions`)
}


export default {
    findAll: findAll,
    find: find,
    delete: deleteGenre,
    update : updateGenre,
    create: createGenre,
    fanfics: displayFanfictions
}