import JwtDecode from 'jwt-decode';
import React, { useEffect, useState } from 'react';
import { Link, useHistory } from 'react-router-dom';
import { toast } from 'react-toastify';
import Field from '../../components/Form/Field';
import chaptersAPI from '../../services/chaptersAPI';
import FanfictionsAPI from '../../services/fanfictionsAPI';

const UploadChapterPage = ({match}) => {
    var id = match.params.id;

    const history = useHistory()

    const [authorId, setAuthorId] = useState(0);
    
    const checkUser = async (myId) => {
        try{
            const {author} = await FanfictionsAPI.find(myId);
            setAuthorId(author.id);

            const token = window.localStorage.getItem("authToken")

            const jwtData = JwtDecode(token)

            const currentUserId = jwtData.user

            if(currentUserId !== author.id){
                toast.error("You do not have permission to upload on this fanfiction")
                return history.go(-1)
            }
        }catch(error){
            toast.error("The fanfiction couldn't be found / An error occured")
            history.go(1)
        }
    }

    useEffect(()=>{
        checkUser(id);
    },[authorId])

    const [chapter, setChapter] = useState({
        title: "",
        link: "",
        chapter: 0,
        addedAt: new Date,
        fanfiction: `/api/fanfictions/${ id }`
    });

    const [errors, setErrors ] = useState({
        title: "",
        link: "",
        chapter: "",
        addedAt: "",
        fanfiction: ""
    });

    const handleChange = (event) => {
        const {name, value} = event.currentTarget
        setChapter({...chapter, [name]:value})
    }

    const handleChapterChange = (event) => {
        const {name, value} = event.currentTarget
        setChapter({...chapter, [name]:parseInt(value)})
    }

    const handleSubmit = async (event) => {
        event.preventDefault()
        try{
            await chaptersAPI.create(chapter)
            toast.success("The chapter has been uploaded successfully")
            history.replace(`/fanfictions/${ id }`)
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
            <h1>Create a chapter</h1>

            <form onSubmit={ handleSubmit }>
                <Field
                    name="title"
                    label="Title"
                    placeholder="Title of your chapter"
                    value={ chapter.title }
                    onChange={ handleChange }
                    error={ errors.title }
                />
                <Field
                    name="link"
                    label="URL"
                    placeholder="Link to your chapter"
                    value={ chapter.link }
                    onChange={ handleChange }
                    error={ errors.link }
                />
                <Field
                    name="chapter"
                    type="number"
                    label="Chapter"
                    placeholder="Number of your chapter"
                    value={ chapter.chapter }
                    onChange={ handleChapterChange }
                    error={ errors.chapter }
                />
                <div className="form-group">
                    <button type="submit" className="btn btn-success">Save</button>
                    <Link to="/admin/chapters" className="btn btn-secondary">Back</Link>
                </div>
            </form>
        </>
     );
}
 
export default UploadChapterPage;