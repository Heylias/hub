import JwtDecode from 'jwt-decode';
import React, { useEffect, useState } from 'react';
import { Link, useHistory } from 'react-router-dom';
import { toast } from 'react-toastify';
import Field from '../../components/Form/Field';
import userAPI from '../../services/userAPI';

const PasswordChangePage = ({match}) => {
    var id = match.params.id

    const history = useHistory()

    const [user, setUser] = useState({
        id,
        pseudonym: "",
        email: ""
    });

    const [errors, setErrors] = useState({
        id: "",
        pseudonym: "",
        email: "",
        password: "",
        passwordConfirm: "",
    });

    const fetchUser = async myId => {
        try{
           const {id, pseudonym, email} = await userAPI.find(myId)
           setUser({id, pseudonym, email})
        }catch(error){
            toast.error("The user couldn't be found / An error occured")
            history.go(-1)
        }
        
    }

    useEffect(()=>{
        fetchUser(id)
    },[id])

    useEffect(() => {
        const token = window.localStorage.getItem("authToken")
        const jwtData = JwtDecode(token)
        const currentUserId = jwtData.user
        
        if(currentUserId != user.id){
            toast.error("You do not have permission to come here.")
            history.replace(`/users/${ id }`)
        }
    }, [])

    const [password, setPassword] = useState({
        newPassword: "",
        passwordConfirm: ""
    });

    const handleChange = (event) => {
        const {name, value} = event.currentTarget
        setPassword({ ...password, [name]: value })
    }

    const handleSubmit = async (event) => {
        event.preventDefault()
        const apiErrors= {}
            
        if(password.newPassword !== password.passwordConfirm){
            apiErrors.passwordConfirm="Your passwords doesn't match when confirming it"
            setErrors(apiErrors)
        }else{
            try{
                await userAPI.update(id, user, password.newPassword)
                toast.success("Your password has been changed successfully")
                history.replace(`/users/${ userId }`)
            }catch({ response }){
                const { violations } = response.data

                if(violations){
                    violations.forEach(({ propertyPath, message }) => {
                        apiErrors[propertyPath] = message
                    })
                    setErrors(apiErrors)
                }
            }
        }
    }

    return ( 
        <>
            <h1>Change password</h1>
            <form onSubmit={ handleSubmit }>
                <Field 
                    name="newPassword"
                    label="Password"
                    type="password"
                    placeholder="Your password"
                    error={ errors.password }
                    value={ user.newPassword }
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
                    <button type="submit" className="btn btn-success">Save</button>
                    <Link to={ `/users/${ id }` } className="btn btn-secondary">Back</Link>
                </div>
            </form>
        </>
     );
}
 
export default PasswordChangePage;