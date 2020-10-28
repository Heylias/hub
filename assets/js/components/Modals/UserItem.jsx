import React from 'react';
import { Link } from 'react-router-dom';

const UserItem = ({user}) => {
    return(
            <div className="card card-user">
                <Link to={ `/users/${user.id}` } className="card-user-head">
                    <img src={ !user.userImage ? `http://placehold.it/300x300` : `/uploads/${user.userImage}`} className="card-user-img-top w-100" alt={ `${ user.pseudonym } avatar` } />
                </Link>
                <div className="card-user-body">
                    <Link to={ `/users/${user.id}` }><h2 className="card-user-title">{ user.pseudonym }</h2></Link>
                    <p className="card-user-text">
                        { !user.fanfictions.length ? (
                            <span>Reader</span>
                        ) : (
                            <span>Writer ({ user.fanfictions.length } works)</span>
                        ) }
                    </p>
                </div>
            </div>
        )
}

export default UserItem;