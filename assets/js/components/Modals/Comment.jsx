import React, { useEffect, useState } from 'react';
import StarRating from './StarRating';
import moment from 'moment';
import JwtDecode from 'jwt-decode';

const Comment = ({comment, handleDelete}) => {

    const formatDate = (str) => moment(str).format('DD/MM/YYYY');

    const token = window.localStorage.getItem("authToken")

    const [currentUser, setCurrentUser] = useState()

    useEffect(() => {
        if(token){
            const jwtData = JwtDecode(token)
            setCurrentUser(jwtData.user)
        }
    }, [])

    return(
        <>
            <div className="row mt-5 comment-item">
                { currentUser === comment.author.id && (
                    <div className="delete-button btn btn-danger" onClick={ handleDelete }>X</div>
                ) }
                <div className="col-md-3 col-sm-12 p-2">
                    <img src={ !comment.author.userImage ? "http://placehold.it/300x300" : comment.author.userImage } className="avatar" alt={ `${ comment.author.pseudonym } avatar` } />
                </div>
                <div className="col">
                    <strong><a href={`/users/${ comment.author.id }`}>{ comment.pseudonym }</a></strong>
                    <StarRating
                        edit={ false }
                        rating={ comment.rating }
                    />
                    { formatDate(comment.creationDate) }
                    <hr />
                    <p>{ comment.commentary }</p>
                </div>
            </div>
        </>
    )
}

export default Comment;