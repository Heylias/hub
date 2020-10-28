import Axios from "axios"
import { TAGS_API } from "../config"

function findAll(){
    return Axios.get(TAGS_API)
                .then(response => response.data['hydra:member'])
                .catch(error => console.log(error))
}

function find(id){
    return Axios.get(`${TAGS_API}/${id}`)
                .then(response=>response.data)
}

function deleteTag(id) {
    return  Axios.delete(`${TAGS_API}/${id}`)
}

function updateTag(id, tag){
    return Axios.put(`${TAGS_API}/${id}`, tag)
}

function createTag(tag){
    return Axios.post(TAGS_API, tag)
}

function displayFanfiction(id){
    return Axios.get(`${TAGS_API}/${id}/fictions`)
}


export default {
    findAll: findAll,
    find: find,
    delete: deleteTag,
    update : updateTag,
    create: createTag,
    displayFanfic: displayFanfiction
}