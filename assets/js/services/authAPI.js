import Axios from "axios"
import jwtDecode from "jwt-decode"
import { LOGIN_API } from "../config"

function logout(){
    window.localStorage.removeItem("authToken")
    delete Axios.defaults.headers['Authorization']
}


function authenticate(credentials){
    return Axios
            .post(LOGIN_API, credentials)
            .then(response => response.data.token)
            .then(token => {
                window.localStorage.setItem("authToken", token)
                Axios.defaults.headers["Authorization"]="Bearer " + token

                return true
            })
}

function setup(){
    const token = window.localStorage.getItem("authToken")

    if(token){
        const jwtData = jwtDecode(token)
        
        if((jwtData.exp * 1000) > new Date().getTime()){
            Axios.defaults.headers["Authorization"]="Bearer " + token
        }

    }

}

function isAuthenticated() {
    const token = window.localStorage.getItem("authToken")
    if(token){
        const jwtData = jwtDecode(token)
        if((jwtData.exp * 1000) > new Date().getTime()){
           return true
        }
        return false
    }
    return false
}

export default {
    authenticate : authenticate,
    logout: logout,
    setup: setup,
    isAuthenticated : isAuthenticated
}