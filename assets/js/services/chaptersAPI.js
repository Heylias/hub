import Axios from "axios"
import { CHAPTERS_API } from "../config"

function findAll(){
    return Axios.get(CHAPTERS_API)
                .then(response => response.data['hydra:member'])
                .catch(error => console.log(error))
}

function find(id){
    return Axios.get(`${CHAPTERS_API}/${id}`)
                .then(response=>response.data)
}

function deleteChapter(id) {
    return  Axios.delete(`${CHAPTERS_API}/${id}`)
}

function updateChapter(id, chapter){
    return Axios.put(`${CHAPTERS_API}/${id}`, chapter)
}

function createChapter(chapter){
    return Axios.post(CHAPTERS_API, chapter)
}


export default {
    findAll: findAll,
    find: find,
    delete: deleteChapter,
    update : updateChapter,
    create: createChapter
}