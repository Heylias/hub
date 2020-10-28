import React, { useState, useContext } from 'react'
import authAPI from '../services/authAPI'
import AuthContext from '../contexts/AuthContext'
import Field from '../components/Form/Field'
import { toast } from 'react-toastify'

const LoginPage = (props) => {

    const { setIsAuthenticated } = useContext(AuthContext)

    const [credentials, setCredentials] = useState({
        username: "",
        password: ""
    })

    const [error, setError ] = useState("")

    const handleChange = (event) => {
        const value = event.currentTarget.value 
        const name = event.currentTarget.name

        setCredentials({ ...credentials, [name]:value })
    }

    const handleSubmit = async (event) => {
        event.preventDefault()
        
        try{
            await authAPI.authenticate(credentials)
            setError("")
            setIsAuthenticated(true)
            toast.success("Login successful")
            props.history.replace("/")
        }catch(error){
            setError("No account registered with this email address, or the credentials were wrong.")
            toast.error("An error occured")
        }
    }

    return ( 
        <>
            <div className="row">
                <div className="col-4 offset-4">
                    <h1>Connexion</h1>
                    <form onSubmit={ handleSubmit }>
                        <Field 
                            label="Email address"
                            name="username"
                            value={ credentials.username }
                            onChange={ handleChange }
                            placeholder="Your email address"
                            error={ error }
                        />
                        <Field
                            label="Password"
                            name="password"
                            value={ credentials.password }
                            onChange={ handleChange }
                            type="password"
                            error=""
                        />
                        <div className="form-group">
                            <button className="btn btn-success">Connexion</button>
                        </div>
                    </form>
                </div>
            </div>
        </>
     );
}
 
export default LoginPage;