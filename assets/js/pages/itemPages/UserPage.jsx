import React, { useEffect, useState } from 'react';
import { Link, useHistory } from 'react-router-dom';
import userAPI from '../../services/userAPI';
import { toast } from 'react-toastify';
import { Container, Row, Col } from 'reactstrap';
import FanfictionItem from '../../components/Modals/FanfictionItem';
import JwtDecode from 'jwt-decode';

const UserPage = ({match}) => {

    var id = match.params.id

    const history = useHistory()

    const [user, setUser] = useState({
        id: 0,
        pseudonym: "",
        userImage: "",
        fanfictions: [],
        comments: []
    });

    const fetchUser = async myId => {
        try{
           const {id, pseudonym, userImage, fanfictions, comments} = await userAPI.find(myId)
           setUser({id, pseudonym, userImage, fanfictions, comments})
        }catch(error){
            toast.error("The user couldn't be found / An error occured")
            history.go(-1) 
        }
    }

    useEffect(()=>{
        if(id !== "new"){
            fetchUser(id)
        }
    },[id])

    var currentUserId = 0

    const token = window.localStorage.getItem("authToken")

    if(token){
        const jwtData = JwtDecode(token)
        currentUserId = jwtData.user
    }

    return(
        <Container>
            <Row>
                <Col sm="12">
                    <div className="fanfic_header">
                        <img src={!user.userImage ? `http://placehold.it/300x300` : user.userImage} alt={`${user.pseudonym}'s avatar`} className="avatar mb-3" />
                        <h1>{ user.pseudonym }</h1>
                        { currentUserId === user.id &&
                            <div className="mt-3">
                                <Link to={ `/users/${ user.id }/edit` } className="btn btn-primary">Edit profile</Link>
                                <Link to={ `/users/${ user.id }/password` } className="btn btn-primary">Change password</Link>
                            </div>
                        }
                        <br/>
                        <div className="mt-3">
                            <span className="badge">{ user.comments.length } comments</span>
                        </div>
                    </div>
                </Col>
                <Col sm="12">
                    <hr/>
                    <ul className="fanfic_list">
                        { user.fanfictions.length > 0 ? (
                            <>
                                { user.fanfictions.map(fanfiction => (
                                    <li className="fanfic_item" key={ fanfiction.id }>
                                        <FanfictionItem
                                            fanfiction={ fanfiction }
                                        />
                                    </li>
                                )) }
                            </>
                        ) : (
                            <span className="fanfic_title">
                                No fanfiction yet
                            </span>
                        ) }
                    </ul>
                </Col>
            </Row>
        </Container>
    );
}

export default UserPage;