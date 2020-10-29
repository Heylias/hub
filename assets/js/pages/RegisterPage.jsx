import React, { useState } from 'react';
import Field from '../components/Form/Field';
import { Link } from "react-router-dom";
import { toast } from 'react-toastify';
import userAPI from '../services/userAPI';

const RegisterPage = ({ history }) => {

    const [user, setUser] = useState({
       email: "",
       pseudonym: "",
       password: "",
       passwordConfirm: ""

    })

    const [errors, setErrors] = useState({
       email: "",
       pseudonym: "",
       password: "",
       passwordConfirm: "" 
    })

    // Gestion des changements des inputs dans le formulaire
    const handleChange = (event) => {
        const {name, value} = event.currentTarget
        setUser({ ...user, [name]: value })
    }

    // gestion de la soumission du formulaire 
    const handleSubmit = async (event) => {
        event.preventDefault()
        //console.log(user)
        const apiErrors= {}

        if(user.password !== user.passwordConfirm){
            apiErrors.passwordConfirm="Your passwords doesn't match when confirming it"
            setErrors(apiErrors)
            return
        }

        try{
            await userAPI.create(user)
            setErrors({})
            toast.success("You have been successfully registered")
            history.replace("/login")
        }catch({ response }){
            const { violations } = response.data

            if(violations){
                violations.forEach(({ propertyPath, message }) => {
                    apiErrors[propertyPath] = message
                })
                setErrors(apiErrors)
            }
            toast.error("There were errors in your registration process...")
        }
    }

    return ( 
        <>
            <h1>Registering</h1>
            <form onSubmit={ handleSubmit }>
                <Field 
                    name="email"
                    label="Email address"
                    type="email"
                    placeholder="Your email address"
                    error={ errors.email }
                    value={ user.email }
                    onChange={ handleChange }
                />
                <Field 
                    name="pseudonym"
                    label="Username"
                    type="text"
                    placeholder="Your username"
                    error={ errors.pseudonym }
                    value={ user.pseudonym }
                    onChange={ handleChange }
                />
                <Field 
                    name="password"
                    label="Password"
                    type="password"
                    placeholder="Your password"
                    error={ errors.password }
                    value={ user.password }
                    onChange={ handleChange }
                />
                <Field 
                    name="passwordConfirm"
                    label="Confirm your password"
                    type="password"
                    placeholder="Confirm your password"
                    error={ errors.passwordConfirm }
                    value={ user.passwordConfirm }
                    onChange={ handleChange }
                />
                 <div className="form-group">
                    <button type="submit" className="btn btn-success">Register</button>
                    <Link to="/login" className="btn btn-secondary">I already have an account</Link>
                </div>
            </form>
        </>
     );
}
 
export default RegisterPage;