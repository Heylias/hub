import React from 'react';
import { Link } from 'react-router-dom';
import StarRating from './StarRating';
import moment from 'moment';
import { Badge, Col, Row } from 'reactstrap';

const FanfictionItem2 = ({fanfiction}) => {
    var latestChapters = fanfiction.chapters.sort((a, b) => b.addedAt > a.addedAt ? 1 : -1);

    const maxLength = 40;

    if(fanfiction.title.length > maxLength){
        fanfiction.title = fanfiction.title.substring(0, maxLength)
        fanfiction.title = fanfiction.title.substring(0,
        Math.min(fanfiction.title.length, fanfiction.title.lastIndexOf(" "))) + " . . ."
    }

    const interval = (str) => moment(str).startOf('hour').fromNow();

    return ( 
        <Row>
            <Col className="fanfic_col" xs="auto">
                <Link to={`/fanfictions/${ fanfiction.id }`}><img src={ fanfiction.coverImage } className="fanfic_cover" /></Link>
                { fanfiction.comments.length !== 0 ? 
                    (
                        <span className="fanfic_rating">
                            <StarRating
                                edit={ false }
                                rating={ fanfiction.avgRatings }
                            />
                        </span>
                    ) : 
                    <span className="fanfic_rating">unrated</span> 
                }
            </Col>
            <Col className="fanfic_col">
                <Link to={`/fanfictions/${ fanfiction.id }`} className="fanficItem_title">{ fanfiction.title }</Link>
                <div className="fanfic_stats">
                    { latestChapters.slice(0, 1).map((latest, index) => (
                        <p key={ index }><i className="far fa-clock mr-2" /><span>last updated: { interval(latest.addedAt) }</span></p>
                    )) }
                    <p><i className="far fa-comment-alt mr-2" /><span>{ fanfiction.comments.length } comments</span></p>
                </div>
                <div className="fanfic_chapters">
                    {(fanfiction.chapters.sort((a,b) => b.chapter > a.chapter ? 1 : -1).slice(0, 3)
                                        .map((chapter, index) => (
                        <p key={ index }>
                            <Link to={ chapter.link }>ch { chapter.chapter }</Link>
                        </p>
                    )))}
                </div>
                <div className="fanfic_badges">
                    <div className="genres">
                        {(fanfiction.genres.slice(0, 3).map((genre, index) => (
                            <span className="badget badge-light" key={ index }>{ genre.name }</span>
                        )))}
                    </div>
                </div>
            </Col>
        </Row>
     );
}
 
export default FanfictionItem2;