import React, { useState } from 'react';
import commentsAPI from './../../services/commentsAPI';
import JwtDecode from 'jwt-decode';
import ReactStars from 'react-rating-stars-component';
import { useHistory } from 'react-router'
import Textarea from './Textarea';

const CommentForm = ({fanfiction}) => {
    const history = useHistory()

    const token = window.localStorage.getItem("authToken")

    const jwtData = JwtDecode(token)

    const currentUser = jwtData.user

    const [newComment, setNewComment] = useState({
        commentary: '',
        author: `api/users/${currentUser}`,
        creationDate: new Date,
        rating: 0
    })

    const handleChange = (event) => {
        const {name, value} = event.currentTarget
        setNewComment({...newComment, [name]: value})
    }

    const handleChangeRating = (newRating) => {
        setNewComment({...newComment, rating: newRating})
    }

    const handleSubmit = async (event) => {
        event.preventDefault();
        try{
            await commentsAPI.create(newComment, fanfiction);
            history.go(0)
        }catch({response}){
            const {violations}=response.data
            console.log(response)
        }
    }

    return ( 
        <>
            <div className="row mt-5 comment-item">
                <form method="post" onSubmit={ handleSubmit }>
                    <ReactStars
                        count={ 5 }
                        onChange={ handleChangeRating }
                        size={ 15 }
                        edit={ true }
                        isHalf={ false }
                        value={ 0 }
                        emptyIcon={ <i className="fas fa-star"></i> }
                        filledIcon={ <i className="fas fa-star"></i> }
                        activeColor="#E44D2E"
                    />
                    <hr/>
                    <Textarea
                        placeholder="Your comment"
                        name="commentary"
                        onChange={ handleChange }
                    />
                    <button type="submit">Send</button>
                </form>
            </div>
        </>
     );
}
 
export default CommentForm;