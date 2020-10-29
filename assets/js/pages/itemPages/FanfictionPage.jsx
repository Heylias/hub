import React, { useState, useEffect, useContext } from 'react';
import { Link, useHistory } from 'react-router-dom';
import FanfictionsAPI from '../../services/fanfictionsAPI';
import { toast } from 'react-toastify';
import { Container, Row, Col } from 'reactstrap';
import ChapterList from '../../components/Modals/ChapterList';
import CommentForm from '../../components/Form/CommentForm';
import Comment from '../../components/Modals/Comment';
import AuthContext from '../../contexts/AuthContext';
import commentsAPI from '../../services/commentsAPI';
import StarRating from '../../components/Modals/StarRating';
import JwtDecode from 'jwt-decode';
import DotLoader from '../../components/Loader/DotLoader';

const FanfictionPage = ({match}) => {

    var id = match.params.id;
    
    const history = useHistory()

    const { isAuthenticated } = useContext(AuthContext)

    var currentUserId = 0

    const token = window.localStorage.getItem("authToken")

    if(token){
        const jwtData = JwtDecode(token)
        currentUserId = jwtData.user
    }

    const [fanfiction, setFanfiction] = useState({
        id: "",
        title: "",
        summary: "",
        author: "",
        language: "",
        coverImage: "",
        tags: [],
        chapters: [],
        comments: [],
        genres: [],
        avgRatings: ""
    });

    const [loading, setLoading] = useState(true)

    const fetchFanfiction = async myId => {
        try{
           const {id, title, summary, author, language, coverImage, tags, chapters, comments, genres, avgRatings} = await FanfictionsAPI.find(myId)
           setFanfiction({id, title, summary, author, language, coverImage, tags, chapters, comments, genres, avgRatings})
           setLoading(false)
        }catch(error){
            toast.error("The fanfiction couldn't be found / An error occured")
            history.go(-1)
        }
    }

    useEffect(()=>{
        fetchFanfiction(id)
    },[id])

    const handleCommentDelete = (id) => {
        const originalComments = [...fanfiction.comments]
        setFanfiction(fanfiction.comments.filter(comment => comment.id !== id))
        commentsAPI.delete(id)
            .catch(error => {
                setCustomers(originalComments)
                console.log(error.response)
            })
        
        // pageHistory.go(0)
    }

    return(
        <Container className="mb-5">
                {(!loading) ? (
                <>
                    <Row>
                        <Col sm="12">
                            <h1 className="fanfic_title">{ fanfiction.title }</h1>
                            <div className="fanfic_header">
                                <span className="fanfic_cover-image">
                                    <img src={ !fanfiction.coverImage ? `http://placehold.it/300x300` : fanfiction.coverImage } className="fanfic_cover" alt=""/>
                                </span>
                                <div className="fanfic_details">
                                    <h5 className="fanfic_title">
                                        Author: <Link to={`/users/${ fanfiction.author.id }`}>{ fanfiction.author.pseudonym }</Link>
                                    </h5>
                                    <h5>{ fanfiction.language.short } | { fanfiction.language.name }</h5>
                                    <span className="fanfic_title">Summary</span><hr/>
                                    <p>{ fanfiction.summary }</p>
                                    { currentUserId === fanfiction.author.id &&
                                        <div className="mt-3">
                                            <Link to={ `/fanfictions/${ fanfiction.id }/edit` } className="btn btn-primary">Edit fanfiction</Link>
                                            <Link to={ `/fanfictions/${ fanfiction.id }/upload` } className="btn btn-primary">Add chapter</Link>
                                        </div>
                                    }
                                </div>
                                { fanfiction.comments.length !== 0 ? 
                                    (
                                        <span className="fanfic_rating">
                                            <StarRating
                                                edit={ false }
                                                rating={ fanfiction.avgRatings }
                                            />
                                        </span>
                                    ) : (
                                    <span className="fanfic_rating">unrated</span> 
                                    )
                                }
                            </div>
                            <hr/>
                        </Col>
                        <Col sm="12" md="5" className="fanfic_tags">
                            <hr/>
                            <h5>Genres</h5>
                            <ul className="badges-list">
                                { fanfiction.genres.map((genre, index) => (
                                    <li className="badget badge-light" key={ index }>{ genre.name }</li>
                                )) }
                            </ul>
                            <hr/>
                            <h5>Tags</h5>
                            <ul className="badges-list">
                                { fanfiction.tags.map((tag, index) => (
                                    <li className="badget badge-light" key={ index }>{ tag.name }</li>
                                )) }
                            </ul>
                        </Col>
                        <Col sm="12" md="7">
                            <div className="chapters_table">
                                <ChapterList chapters={ fanfiction.chapters } />
                            </div>
                        </Col>
                    </Row>
                    <hr/>
                    <div className="comments-section">
                        {isAuthenticated && (
                            <CommentForm
                                fanfiction={ fanfiction }
                            />
                        )}
                        {fanfiction.comments.sort((a, b) => a.creationDate > b.creationDate ? 1:-1)
                            .map((comment, index) => (
                                <Comment
                                    comment={ comment }
                                    key={ index }
                                    handleDelete={ () => handleCommentDelete(comment.id) }
                                />
                        ))}
                    </div>
                </>
                ) : (
                    <DotLoader />
                )}
        </Container>
    )
}

export default FanfictionPage;