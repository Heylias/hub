import moment from 'moment';
import React, { useState } from 'react';

const ChapterList = ({chapters}) => {
    const lastUpdate = chapters.sort((a, b) => b.addedAt > a.addedAt ? 1 : -1).slice(0,1).map(chapter => chapter.addedAt);
    
    const [chapterList, setChapterList] = useState(chapters.sort((a, b) => b.chapter > a.chapter ? 1 : -1))

    const [chaptersDisplayed, setChaptersDisplayed] = useState(chapterList.slice(0,10))

    const formatDate = (str) => moment(str).format('DD/MM/YYYY');

    const handleDisplay = (event) => {
        setChaptersDisplayed(chapterList)
        event.currentTarget.style = {display: 'none'}
    }

    return(
        <div className="chapters">
            <div className="chapters_infos">
                <h5>Chapters ({ chapterList.length })</h5>
                <span>Last updated { formatDate( lastUpdate ) }</span>
            </div>
            <ul className="chapter-list">
                { chaptersDisplayed.map((chapter, index) => (
                    <li key={ index } className="chapter" >
                        <a href={ chapter.link }>
                            <p className="chapter-num"> ch.{ chapter.chapter }</p>
                            {formatDate(chapter.addedAt)}
                        </a>
                    </li>
                )) }
            </ul>
            { chapterList.length > chaptersDisplayed.length && (
                <button onClick={ handleDisplay } className="show-more-link">Show more <i className="fas fa-chevron-down" /></button>
            ) }
        </div>
    )
}

export default ChapterList;