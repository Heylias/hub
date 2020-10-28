import React, { useContext, useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import { toast } from 'react-toastify';
import { Col, Row } from 'reactstrap';
import DotLoader from '../components/Loader/DotLoader';
import FanfictionItem from '../components/Modals/FanfictionItem';
import AuthContext from '../contexts/AuthContext';
import fanfictionsAPI from '../services/fanfictionsAPI';

const HomePage = () => {
    const [bestFanfictions, setBestFanfictions] = useState([]);

    const [recentFanfictions, setRecentFanfictions] = useState([]);

    const [loading, setLoading] = useState(true);

    const fetchFanfictions = async () => {
        try{
            const bestFanfics = await fanfictionsAPI.findAll();
            setBestFanfictions(bestFanfics.sort((a, b) => b.avgRatings > a.avgRatings ? 1 : -1)
                                            .slice(0,5));

            const recentFanfics = await fanfictionsAPI.recentUploads();
            setRecentFanfictions(recentFanfics.slice(0,5));

            setLoading(false);
        }catch(error){
           toast.error("Impossible to load the fanfictions / An unexpected error occured");
        }
    }
    
    useEffect(() => {
        fetchFanfictions();
    }, []);

    return ( 
        <> 
            {(!loading) ? (
                <>
                    <div className="title">
                        <h1>Hubfiction</h1>
                        <h5>The library of fanfictions</h5>
                    </div>
                    <div className="home-row">
                        <div className="home-title_link">
                            <h2 className="fanfic_title">Best rated fanfictions</h2>
                            <Link to="/fanfictions/best" className="list-more">more &#11166;</Link>
                        </div>
                        <hr/>
                        <ul className="fanfic_list">
                            {bestFanfictions.map((bestFanfic, index) => (
                                <li className="fanfic_item" key={ index }>
                                    <FanfictionItem
                                        fanfiction={ bestFanfic }
                                    />
                                </li>
                            ))}
                        </ul>
                    </div>
                    <div className="home-row">
                        <div className="home-title_link">
                            <h2 className="fanfic_title">Recent uploads</h2>
                            <Link to="/fanfictions/latest" className="list-more">more &#11166;</Link>
                        </div>
                        <hr/>
                        <ul className="fanfic_list">
                            {recentFanfictions.map((recentFanfic, index) => (
                                <li className="fanfic_item" key={ index }>
                                    <FanfictionItem
                                        fanfiction={ recentFanfic }
                                    />
                                </li>
                            ))}
                        </ul>
                    </div>
                </>
            ) : (
                <DotLoader />
            )
            }
        </>
     );
}
 
export default HomePage;