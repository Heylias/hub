import React, { useContext, useEffect, useState } from 'react';
import fanfictionsAPI from '../../services/fanfictionsAPI';
import Pagination from '../../components/Pagination';
import { toast } from 'react-toastify';
import FanfictionItem from '../../components/Modals/FanfictionItem';
import DotLoader from '../../components/Loader/DotLoader';

const FanfictionsPage = () => {
    const [fanfictions, setFanfictions] = useState([]);

    const [currentPage, setCurrentPage] = useState(1);

    const [loading, setLoading] = useState(true);

    const fetchFanfictions = async () => {
        try{
            const data = await fanfictionsAPI.findAll();
            setFanfictions(data.sort((a, b) => b.avgRatings > a.avgRatings ? 1 : -1));
            setLoading(false);
        }catch(error){
           toast.error("Impossible to load the fanfictions / An unexpected error occured");
        }
    }
    
    useEffect(() => {
        fetchFanfictions();
    }, [currentPage]);

    const handlePageChange = (page) => {
        setFanfictions([]);
        setCurrentPage(page);
    }

    const itemsPerPage = 25;

    const paginatedFanfictions = Pagination.getData(fanfictions, currentPage, itemsPerPage);

    return (
        <>
            <div className="d-flex justify-content-between align-items-center">
                <h1>Best Rated</h1>
            </div>
            <ul className="fanfic_list">
            {(!loading) ? (
                paginatedFanfictions.map((fanfiction, index) => (
                    <li className="fanfic_item mx-3" key={ index }>
                        <FanfictionItem
                            fanfiction={ fanfiction }
                        />
                    </li>
                ))) : (
                    <DotLoader />
                )}
            </ul>
            { itemsPerPage < fanfictions.length &&
                <Pagination 
                    currentPage={ currentPage }
                    itemsPerPage={ itemsPerPage }
                    length={ fanfictions.length }
                    onPageChanged={ handlePageChange }
                />
            }
        </>
    );
}
export default FanfictionsPage;