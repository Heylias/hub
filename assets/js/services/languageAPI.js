import Axios from "axios"
import { LANGUAGE_API } from "../config"

function findAll(){
    return Axios.get(LANGUAGE_API)
                .then(response => response.data['hydra:member'])
                .catch(error => console.log(error))
}

function findRecentUploads(){
    return Axios.get(`${LANGUAGE_API}?order[chapters.addedAt]=desc`)
}

function find(id){
    return Axios.get(`${LANGUAGE_API}/${id}`)
                .then(response=>response.data)
}

function deleteLanguage(id) {
    return  Axios.delete(`${LANGUAGE_API}/${id}`)
}

function updateLanguage(id, language){
    return Axios.put(`${LANGUAGE_API}/${id}`, language)
}

function createLanguage(language){
    return Axios.post(LANGUAGE_API, language)
}

function displayFictions(id){
    return Axios.get(`${LANGUAGE_API}/${id}/fanfictions`)
}

export default {
    findAll: findAll,
    find: find,
    delete: deleteLanguage,
    update : updateLanguage,
    create: createLanguage,
    fictions: displayFictions
}