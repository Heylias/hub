import Axios from "axios"
import { FICTIONS_API } from "../config"

function findAll(){
    return Axios.get(FICTIONS_API)
                .then(response => response.data['hydra:member'])
                .catch(error => console.log(error))
}

function findRecentUploads(){
    return Axios.get(`${FICTIONS_API}?order[chapters.addedAt]=desc`)
                .then(response => response.data['hydra:member'])
}

function findBestRated(){
    return Axios.get(`${FICTIONS_API}?order[avgRatings]=desc`)
                .then(response => response.data['hydra:member'])
}

function find(id){
    return Axios.get(`${FICTIONS_API}/${id}`)
                .then(response=>response.data)
                .catch(error => console.log(error))
}

function deleteFiction(id) {
    return  Axios.delete(`${FICTIONS_API}/${id}`)
}

function updateFiction(id, fiction){
    return Axios.put(`${FICTIONS_API}/${id}`, fiction)
}

function createFiction(fiction){
    return Axios.post(FICTIONS_API, fiction)
}

function displayComments(id){
    return Axios.get(`${FICTIONS_API}/${id}/comments`)
}

function displayTags(id){
    return Axios.get(`${FICTIONS_API}/${id}/tags`)
}

function displayGallery(id){
    return Axios.get(`${FICTIONS_API}/${id}/gallery`)
}

function displayAuthor(id){
    return Axios.get(`${FICTIONS_API}/${id}/author`)
}

function getChapters(id){
    return Axios.get(`${FICTIONS_API}/${id}/chapters`)
}

function getGenres(id){
    return Axios.get(`${FICTIONS_API}/${id}/genres`)
}

export default {
    findAll: findAll,
    recentUploads: findRecentUploads,
    bestRated: findBestRated,
    find: find,
    delete: deleteFiction,
    update : updateFiction,
    create: createFiction,
    comment: displayComments,
    tags: displayTags,
    gallery: displayGallery,
    author: displayAuthor,
    chapters: getChapters,
    genres: getGenres
}