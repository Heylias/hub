import Axios from "axios"
import { COMMENTS_API } from "../config"

function findAll(){
    return Axios.get(COMMENTS_API)
                .then(response => response.data['hydra:member'])
                .catch(error => console.log(error))
}

function find(id){
    return Axios.get(`${COMMENTS_API}/${id}`)
                .then(response=>response.data)
}

function deleteComment(id) {
    return  Axios.delete(`${COMMENTS_API}/${id}`)
}

function updateComment(id, comment){
    return Axios.put(`${COMMENTS_API}/${id}`, comment)
}

function createComment(comment, fanfiction){
    return Axios.post(COMMENTS_API, {...comment, fanfiction:`api/fanfictions/${fanfiction.id}`})
}

function displayAuthor(id){
    return Axios.get(`${COMMENTS_API}/${id}/author`)
}


export default {
    findAll: findAll,
    find: find,
    delete: deleteComment,
    update : updateComment,
    create: createComment,
    author: displayAuthor
}