import JwtDecode from 'jwt-decode';
import React, { useEffect, useState } from 'react';
import { Link, useHistory } from 'react-router-dom';
import { toast } from 'react-toastify';
import Field from '../../components/Form/Field';
import userAPI from '../../services/userAPI';

const EditUserPage = ({match}) => {
    var id = match.params.id

    const history = useHistory()

    const [user, setUser] = useState({
        id,
        pseudonym: "",
        email: "",
        userImage: ""
    });

    const [password, setPassword] = useState({
        password: ""
    })

    const [errors, setErrors] = useState({
        id: "",
        pseudonym: "",
        email: "",
        userImage: "",
        password: ""
    });

    const fetchUser = async myId => {
        try{
           const {id, pseudonym, email, userImage, password} = await userAPI.find(myId)
           setUser({id, pseudonym, email, userImage})
           setPassword({password})
        }catch(error){
            toast.error("The user couldn't be found / An error occured")
            history.replace(`/users/${ id }`) 
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
            history.go(-1)
        }
    }, [])

    const handleChange = (event) => {
        const {name, value} = event.currentTarget
        setUser({...user, [name]:value})
    }

    const handleSubmit = async (event) => {
        event.preventDefault()
        try{
            await userAPI.update(id, user, password.password)
            toast.success("Your profile has been changed successfully")
            history.replace(`/users/${ id }`)
        }catch({response}){
            const {violations} = response.data
            if(violations){
                const apiErrors = {}
                violations.forEach(({propertyPath, message}) => {
                    apiErrors[propertyPath] = message
                })
                setErrors(apiErrors)
            }
        }
    }

    return ( 
        <>
            <h1>Edit profile</h1>
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
                    name="userImage"
                    label="Profile Picture"
                    type="text"
                    placeholder="Your profile picture (URL)"
                    error={ errors.userImage }
                    value={ user.userImage }
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
 
export default EditUserPage;