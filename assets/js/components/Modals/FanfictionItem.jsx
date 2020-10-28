import React from 'react';
import { Link } from 'react-router-dom';
import StarRating from './StarRating';

const FanfictionItem = ({fanfiction}) => {
    const latestChapters = fanfiction.chapters.sort((a, b) => b.chapter > a.chapter ? 1 : -1).slice(0,1);

    const maxLength = 30;

    if(fanfiction.title.length > maxLength){
        fanfiction.title = fanfiction.title.substring(0, maxLength)
        fanfiction.title = fanfiction.title.substring(0,
        Math.min(fanfiction.title.length, fanfiction.title.lastIndexOf(" "))) + " . . ."
    }

    return(
        <>
            <div>
                <Link to={`/fanfictions/${ fanfiction.id }`}>
                    <img src={!fanfiction.coverImage ? `http://placehold.it/150x228` : fanfiction.coverImage} alt="" className="fanfic_cover" />
                </Link>
                <br/>
                <Link to={`/fanfictions/${ fanfiction.id }`} className="fanficItem_title">{ fanfiction.title}</Link>
            </div>
            { latestChapters.map((chapter, index) => (
                <p key={ index }><Link to={ chapter.link } className="chapter_link">ch { chapter.chapter }</Link></p>
            )) }
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
        </>
    )
}

export default FanfictionItem;