import JwtDecode from 'jwt-decode'
import React, { useContext } from 'react'
import { Route, Redirect } from "react-router-dom"
import { toast } from 'react-toastify'
import AuthContext from '../contexts/AuthContext'

const AdminRoute = (props) => {
    const { isAuthenticated } = useContext(AuthContext)

    const token = window.localStorage.getItem("authToken")

    const jwtData = JwtDecode(token)

    const currentUserRole = jwtData.roles
    
    if(isAuthenticated) {
      if(currentUserRole.includes("ROLE_ADMIN")){
         return <Route path={ props.path } component={ props.component } />
      } else {
         toast.error("You do not have the permission to access the admin panel")
         return <Redirect to="/" />
      }
    } else {
       return <Redirect to="/admin/login" />
    }
}
 
export default AdminRoute;