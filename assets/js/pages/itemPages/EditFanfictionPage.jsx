import React, { useState, useEffect } from 'react';
import { Link, useHistory } from 'react-router-dom';
import Field from '../../components/Form/Field';
import { toast } from 'react-toastify';
import fanfictionsAPI from '../../services/fanfictionsAPI';
import LanguageSelect from '../../components/Form/LanguageSelect';
import TagsSelect from '../../components/Form/TagsSelect';
import GenresSelect from '../../components/Form/GenresSelect';
import JwtDecode from 'jwt-decode';
import Textarea from '../../components/Form/Textarea';

const EditFanfictionPage = ({match}) => {
    var { id = "new" } = match.params;

    const history = useHistory()

    const token = window.localStorage.getItem("authToken")
    const jwtData = JwtDecode(token)
    const currentUserId = jwtData.user

    const [fanfiction, setFanfiction] = useState({
        title: "",
        author: "",
        summary: "",
        language: "",
        coverImage: "",
        genres: [],
        tags: []
    });

    const [errors, setErrors ] = useState({
        title: "",
        author: "",
        summary: "",
        language: "",
        coverImage: "",
        genres: [],
        tags: []
    });

    const [editing, setEditing] = useState(false);

    const fetchFanfiction = async id => {
        try{
           const {title, summary, author, language, coverImage, genres, tags} = await fanfictionsAPI.find(id)
           setFanfiction({title, summary, author: `/api/users/${author.id}`, language: `/api/languages/${language.id}`, coverImage, genres, tags})
        }catch(error){
            toast.error("The fanfiction couldn't be charged / An error occured")
            history.go(-1)
        }
    }

    useEffect(()=>{
        if(id !== "new"){
            setEditing(true)
            fetchFanfiction(id)
        }else{
            setFanfiction({...fanfiction, author:`api/users/${ currentUserId }`})
        }
    },[id])

    const checkUser = async () => {
        if(`/api/users/${currentUserId}` != fanfiction.author){
            toast.error("You do not have permission to come here.")
            // history.replace(`/fanfictions/${ id }`)
        }else{
            console.log('ok')
        }
    }

    useEffect(() => {
        checkUser()
    }, [])
    const handleChange = (event) => {
        const {name, value} = event.currentTarget
        setFanfiction({...fanfiction, [name]:value})
    }

    const handleSelectChange = (event) => {
        const {name} = event.currentTarget
        const options = event.target.options;
        var valueList = [];
        for (var i = 0, l = options.length; i < l; i++) {
          if (options[i].selected) {
            valueList.push(options[i].value);
          }
        }
        setFanfiction({...fanfiction, [name]:valueList})
    }

    const handleSubmit = async (event) => {
        event.preventDefault()
        try{
            if(editing){
                await fanfictionsAPI.update(id, fanfiction)
                toast.success("The fanfiction has been edited successfully")
                history.replace(`/fanfictions/${id}`)
            }else{
                await fanfictionsAPI.create(fanfiction)
                toast.success("The fanfiction has been created successfully")
                history.replace("/fanfictions")
            }
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

    return(
        <>
            {!editing ? <h1>Create a new fanfiction</h1> : <h1>Edit a fanfiction</h1>}

            <form onSubmit={ handleSubmit }>
                <Field 
                    name="title"
                    label="Title"
                    placeholder="Title of your fanfiction"
                    value={ fanfiction.title }
                    onChange={ handleChange }
                    error={ errors.title }
                />
                <Textarea
                    name="summary"
                    label="Summary"
                    placeholder="Summary of the fanfiction"
                    value={ fanfiction.summary }
                    onChange={ handleChange }
                    error={ errors.summary }
                />
                <LanguageSelect
                    value={ fanfiction.language }
                    error={ errors.language }
                    onChange={ handleChange }
                />
                <Field 
                    name="coverImage"
                    type="text"
                    label="Cover image"
                    placeholder="Insert cover image"
                    value={ fanfiction.coverImage }
                    onChange={ handleChange }
                    error={ errors.coverImage }
                />
                <GenresSelect
                    value={ fanfiction.genres }
                    error={ errors.genres }
                    onChange={ handleSelectChange }
                />
                <TagsSelect
                    value={ fanfiction.tags }
                    error={ errors.tags }
                    onChange={ handleSelectChange }
                />
                <div className="form-group">
                    <button type="submit" className="btn btn-success">Save</button>
                    <Link to="/fanfictions" className="btn btn-secondary">Back</Link>
                </div>
            </form>
        </>
    );
}

export default EditFanfictionPage;